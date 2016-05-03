<?php

namespace Hris\PayrollBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;

use Hris\PayrollBundle\Entity\PayTaxMatrix;

use Hris\PayrollBundle\Entity\PayTaxRate;
use Hris\PayrollBundle\Entity\PayTaxStatus;
use Hris\PayrollBundle\Entity\PayPeriod;

use DateTime;

class Pay13thController extends CrudController
{
	public function __construct()
	{
		$this->route_prefix = 'hris_payroll_thirteenth_view';
		$this->title = 'Payroll Record';

		$this->list_title = 'Payroll Record';
		$this->list_type = 'dynamic';
        $this->repo = 'HrisPayrollBundle:Pay13th';
	}

    protected function getObjectLabel($object) 
    {
        if ($object == null){
            return '';
        }
    } 

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List');

        $twig_file = 'HrisPayrollBundle:Pay13th:index.html.twig';

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        return $this->render($twig_file, $params);
    }

    protected function newBaseClass() {
        return new Pay13th();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('catalyst_grid');
        return array(
            // $grid->newJoin('period', 'period', 'getPayrollPeriod', 'left'),
            $grid->newJoin('employee', 'employee', 'getEmployee'),

        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');
        return array( 
            $grid->newColumn('Employee', 'getDisplayName', 'last_name','employee'),
            $grid->newColumn('Year', 'getYear', 'year','o'),
            $grid->newColumn('Total', 'getTotal', 'total_amount','o', array($this,'formatPrice')),
        );
    }



    public function update($o, $data, $is_new = false)
    {

     
    }

    public function addEntryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();
        $pm = $this->get('hris_thirteenth');
        $obj = $pm->get13thMonthDetails($id);

        //error_log(print_r($data,true));
        
        $start = new DateTime($data['pay_from']);
        $end = new DateTime($data['pay_to']);


        $period = $this->generatePeriod($obj->getEmployee(),$start,$end);
        
        if($period != null){
            $pd = $pm->generate13thMonthEntry($obj->getEmployee(), $period);
                     $pd->setEarning($data['earnings']);
                     $pd->setDeduction($data['deduction']);
                     $pd->setTotal($data['earnings'] - $data['deduction']);

            $obj->addEarningEntry($pd);
            $total = $obj->getSubTotal()/12;

            //$obj->setTotalTaxable($total);
            $pm->applyTax($obj);
            $em->persist($obj);
            $em->flush();
            $response = ['status'=> 'Success', 'data'=> $obj->toData()];
            return new JsonResponse($response);
        }else {
            $response = ['status'=> 'Fail'];
            return new JsonResponse($response);
        }
    }

    protected function generatePeriod($employee, $start, $end){
         $pc = $this->get('hris_payroll_compute');
         $conf = $this->get('catalyst_configuration');
         $schedule = $employee->getPaySchedule();
         $valid = false;

         switch($schedule->getName()){
            case PayPeriod::TYPE_SEMIMONTHLY :
                $cutoffs = json_decode($conf->get('hris_payroll_semimonthly_sched'), true);
                $startday =  $start->format('t') ==  $start->format('j')? 0 : $start->format('j');
                $endday =  $end->format('t') ==  $end->format('j')? 0 : $end->format('j');
                
                $interval = $end->diff($start);
                if((($cutoffs['cutoff_start1'] == $startday &&
                    $cutoffs['cutoff_end1'] == $endday) ||
                   ($cutoffs['cutoff_start2'] == $startday &&
                    $cutoffs['cutoff_end2'] == $endday)) &&
                   ($interval->m < 1 )
                     )
                    $valid = true;
                break;
            case PayPeriod::TYPE_WEEKLY :
                $cutoffs = json_decode($conf->get('hris_payroll_weekly_sched'), true);
                $interval = $end->diff($start);
                if(($cutoffs['cutoff_start'] == $start->format('N') &&
                    $cutoffs['cutoff_end'] == $end->format('N')) &&
                   ($interval->m < 1 &&  $interval->d <=7 )
                     )
                    $valid = true;
                break;
        }
         if($valid){
             return $pc->generatePayPeriod($schedule,$start,$end);
         }
         else {
            return null;
         }
      
    }

    public function callbackGrid($id)
    {
        $params = array(
            'id' => $id,
            // 'route_edit' => $this->getRouteGen()->getEdit(),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'HrisPayrollBundle:Pay13th:action.html.twig',
            $params
        );
    }

    protected function padFormParams(&$params, $object = null)
    {
        $payroll = $this->get('hris_payroll');

        return $params;
    }

    public function lockAction($id)
    {
        $pm = $this->get('hris_thirteenth');
        $obj = $pm->get13thMonthDetails($id);

        $obj->lock();

        $pm->persistPay13th($obj);
        return $this->redirect($this->generateUrl('hris_payroll_thirteenth_details_index', 
            array(
                    'id' => $obj->getID(),
                )));
    }

    public function deleteEntryAction($id)
    {
        $pm = $this->get('hris_thirteenth');
        $em = $this->getDoctrine()->getManager();

        $obj = $pm->get13thMonthEntry($id);
        $pay13th = $obj->getPay13th();
        $em->remove($obj);

        $pm->applyTax($pay13th);
        $em->persist($pay13th);
        $em->flush();

        return $this->redirect($this->generateUrl('hris_payroll_thirteenth_details_index', 
        array(
                'id' => $pay13th->getID()
        )));

    }
}