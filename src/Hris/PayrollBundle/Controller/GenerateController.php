<?php

namespace Hris\PayrollBundle\Controller;

use Catalyst\TemplateBundle\Model\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;

use Hris\PayrollBundle\Entity\PayPayroll;
use Hris\PayrollBundle\Entity\PayPeriod;
use Hris\PayrollBundle\Entity\PayDeductionEntry;
use Hris\PayrollBundle\Entity\PayPayrollPeriod;
use Hris\PayrollBundle\Entity\PayEarningEntry;
use Catalyst\NotificationBundle\Model\NotificationEvent;
use Catalyst\NotificationBundle\Entity\Notification;

use Hris\PayrollBundle\Entity\PayTax;

use DateTime;

class GenerateController extends BaseController
{
    public function __construct()
    {
        $this->route_prefix = 'hris_payroll_generate';
        $this->title = 'Semi-monthly Payroll';

        $this->list_title = 'Semi-monthly Payroll';
        $this->list_type = 'dynamic';
    }

    public function indexAction()
    {
        $conf = $this->get('catalyst_configuration');

        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();
        

        $sched = json_decode($conf->get('hris_payroll_semimonthly_sched'), true);

        $params = $this->getViewParams('', 'hris_payroll_generate_index');
        $this->padFormParams($params);
        $params['sched'] = $sched;
        $params['payroll'] = [];
        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $this->getGridColumns();
        $params['title'] = $this->title;
        // $params['date_from'] = $date_from;
        // $params['date_to'] = $date_to;
        // $params['wdate_from'] = $wdate_from;
        // $params['wdate_to'] = $wdate_to;

        $twig_file = 'HrisPayrollBundle:Generate:index.html.twig';
   
        return $this->render($twig_file, $params); 
        
        //$em = $this->getDoctrine()->getManager();
        //$semi = $em->getRepository('HrisPayrollBundle:PayPeriod')->findOneByName(PayPeriod::TYPE_SEMIMONTHLY);

        //return $this->redirect($this->generateUrl('hris_payroll_generate_filter', array('date_from'=>$fdate_from, 'date_to'=>$fdate_to, 'period' =>$semi->getID())));
    }

    public function filterAction()
    {
        $data = $this->getRequest()->request->all();
        $em = $this->getDoctrine()->getManager();
        // $period = $data['pay_sched'];

        $payroll = $this->get('hris_payroll_compute');
        $pm = $this->get('hris_payroll');
        $settings = $this->get('hris_settings');
        $conf = $this->get('catalyst_configuration');
      
        $sched = $em->getRepository('HrisPayrollBundle:PayPeriod')->findOneByName(PayPeriod::TYPE_SEMIMONTHLY);
        $date_from = $data['date_from'];
        $date_to = $data['date_to'];

        
        $date_from = new DateTime($date_from);
        $date_to = new DateTime($date_to);
        $fdate_from = $date_from->format("Ymd");
        $fdate_to = $date_to->format("Ymd");
  
       

        $employees = $payroll->generatePayroll($sched,$date_from, $date_to);

        $params = $this->getViewParams('', 'hris_payroll_generate_index');
        //$gl = $this->setupGridLoader();
        $this->padFormParams($params);
        $params['sched'] = json_decode($conf->get('hris_payroll_semimonthly_sched'));
        $params['wsched'] = json_decode($conf->get('hris_payroll_weekly_sched'), true);
        $params['payroll'] = $employees;
        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $this->getGridColumns();
        $params['title'] = $this->title;
        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;
   
        $twig_file = 'HrisPayrollBundle:Generate:index.html.twig';
        $this->notify($date_from, $date_to, $sched);
        return $this->render($twig_file, $params);      
    }

    protected function notify($date_from, $date_to, $period)
    {
            $config = $this->get('catalyst_configuration');
            $settings = $this->get('hris_settings');
            $hr = $settings->getDepartment($config->get('hris_hr_department'));

            $msg = sprintf("Payroll Generated for %s employees for period %s to %s. ", 
                    $period->getName(),
                    $date_from->format('m/d/Y'),
                    $date_to->format('m/d/Y'));

            $event = new NotificationEvent();
            $event->notify(array(
                'source'=> 'Payroll Generated',
                'link'=> $this->generateUrl('hris_payroll_view_index'),
                'message'=> $msg,
                'type'=> Notification::TYPE_UPDATE,
                'receipient' => $hr));

                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch('notification.event', $event);
    }

    protected function getObjectLabel($object) {
        
    }

    protected function newBaseClass() {
        return new PayPayroll();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');
        return array(
            $grid->newColumn('Name', '', ''),
            $grid->newColumn('Gross Pay', '', ''),
            $grid->newColumn('Net Pay', '', ''),
        );
    }

    public function update($o, $data, $is_new = false)
    {
        $wfm = $this->get('hris_workforce');
        $pay = $this->get('hris_payroll');
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;

        $settings = $this->get('hris_settings');
        $payroll = $this->get('hris_payroll');

        $params['dept_opts'] = $settings->getDepartmentOptions();
        $params['sched_opts'] = $payroll->getPaySchedPayrollOptions();
        return $params;
    }

    public function detailsAction($id)
    {
        $params = $this->padDetailsParam($id);
        $params['is_taxable'] = array(0 => 'No', 1 =>'Yes');
        $twig_file = 'HrisPayrollBundle:Generate:details.html.twig';
  
        return $this->render($twig_file, $params);
    }


    public function printAction($id)
    {
       
        $twig = "HrisPayrollBundle:Generate:print.html.twig";
        $em = $this->getDoctrine()->getManager();
        $conf = $this->get('catalyst_configuration');
        $media = $this->get('catalyst_media');

        $params = $this->padDetailsParam($id);
        // $params['company_name'] = strtoupper($conf->get('hris_com_info_company_name'));
        // $params['company_website'] = $conf->get('hris_com_info_website');
        // $params['company_address'] = $em->getRepository('CatalystContactBundle:Address')->find($conf->get('hris_com_info_company_address'));
        
        if ($conf->get('hris_com_logo') != '') 
        {
            $path = $media->getUpload($conf->get('hris_com_logo'));

            $str = $path->getURL();
            $str = parse_url($str, PHP_URL_PATH);
            $str = ltrim($str, '/');

            $params['logo'] = $str;
        }
        else
        {
            $params['logo'] = '';
        }


        $pdf = $this->get('catalyst_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
   
    }

    protected function padDetailsParam($id)
    {
        $payroll = $this->get('hris_payroll_compute');
        $pm = $this->get('hris_payroll');
        
        $params = $this->getViewParams('List');
        $params['payroll'] = $pm->getPayPayroll($id);
        
        $params['taxable_earning'] = 0;
        $params['nontaxable_earning'] = 0;
        $params['taxable_deduction'] = 0;
        $params['nontaxable_deduction'] = 0;
        foreach ($params['payroll']->getEarningEntries() as $entry) {
            if($entry->getType() == PayEarningEntry::TYPE_INCENTIVE || $entry->getType() == PayEarningEntry::TYPE_OTHERS)
                $params['earnings'][$entry->getType()][] = $entry;
            else 
                $params['earnings'][$entry->getType()] = $entry->getAmount();

            if($entry->isTaxable())
                $params['taxable_earning'] += $entry->getAmount();
            else
                $params['nontaxable_earning'] += $entry->getAmount();
        }

        foreach ($params['payroll']->getDeductionEntries() as $entry) {

            if($entry->getType() == PayDeductionEntry::TYPE_COMPANYLOAN || $entry->getType() == PayDeductionEntry::TYPE_OTHERS)
                $params['deductions'][$entry->getType()][] = $entry;
            else 
                $params['deductions'][$entry->getType()] = $entry->getAmount();

            
            if($entry->isTaxable())
                $params['taxable_deduction'] += $entry->getAmount();
            else
                $params['nontaxable_deduction'] += $entry->getAmount();
        }

        return $params;
    }

    protected function parseDay($day)
    {
        $now = new Datetime();
        if($day == 0){
             return $now->setDate($now->format('Y'),$now->format('m'), $now->format('t')  );
        }

        if($day > (integer) $now->format('d')){
            return $now->setDate($now->format('Y'),$now->format('m') - 1, $day );
        }else {
            return $now->setDate($now->format('Y'),$now->format('m'), $day );
        }

    }

    protected function parseRange($from, $to){
        $from = $this->parseDay($from);
        $to = $this->parseDay($to);
        // $now = new DateTime();
        if($from > $to){
            $from->setDate($from->format('Y'),$from->format('m') - 1, $from->format('d') );
        }
        return [$from,$to];
    }

    public function addEarningAction($id){
        $pm = $this->get('hris_payroll');
        $pc = $this->get('hris_payroll_compute');
        $payroll = $pm->getPayPayroll($id);
        $data = $this->getRequest()->request->all();
        $pe = $pm->newPayrollEarning();

        $pe->setType(PayEarningEntry::TYPE_OTHERS)
            ->setNotes($data['earning'])
            ->setAmount($data['earning_amount'])
            ->setTaxable($data['is_taxable']);

        $payroll->addEarningEntry($pe);
        $pc->applyTax($payroll);
      
        return new JsonResponse($pe->toData());
    }

    public function addDeductionAction($id){
        $pm = $this->get('hris_payroll');
        $pc = $this->get('hris_payroll_compute');
        $payroll = $pm->getPayPayroll($id);
        $data = $this->getRequest()->request->all();
        $pe = $pm->newPayrollDeduction();

        $pe->setType(PayDeductionEntry::TYPE_OTHERS)
            ->setNotes($data['earning'])
            ->setAmount($data['deduction_amount'])
            ->setTaxable($data['is_taxable']);

        $payroll->addDeductionEntry($pe);
        $pc->applyTax($payroll);
      
        return new JsonResponse($pe->toData());
    }

    public function deleteEarningAction($id){
        $pm = $this->get('hris_payroll');
        $pc = $this->get('hris_payroll_compute');
        $em = $this->getDoctrine()->getManager();

        $pe = $pm->getPayEarning($id);
        $payroll = $pe->getPayroll();
        $payroll->deleteEarningEntry($pe);
        $pc->applyTax($payroll);

        $em->remove($pe);
        $em->flush();

        $payroll_id = $payroll->getID();
         return $this->redirect($this->generateUrl('hris_payroll_details_index', 
            array(
                    'id' => $payroll_id,
                )));
    }

    public function deleteDeductionAction($id){
        $pm = $this->get('hris_payroll');
        $pc = $this->get('hris_payroll_compute');
        $em = $this->getDoctrine()->getManager();

        $pe = $pm->getPayDeduction($id);
        $payroll = $pe->getPayroll();
        $payroll->deleteDeductionEntry($pe);
        $pc->applyTax($payroll);

        $em->remove($pe);
        $em->flush();

        $payroll_id = $payroll->getID();
         return $this->redirect($this->generateUrl('hris_payroll_details_index', 
            array(
                    'id' => $payroll_id,
                )));
    }

    public function lockAction($id)
    {
        $pm = $this->get('hris_payroll');
        $cb = $this->get('hris_cashbond');
        $payroll = $pm->getPayPayroll($id);
      

        $savings = $payroll->getDeductionEntry(PayDeductionEntry::TYPE_CASHBOND);
        if($savings != null){
            $cb->addContribution($savings);
        }
        $payroll->lock();
        $pm->persistPayroll($payroll);
        return $this->redirect($this->generateUrl('hris_payroll_details_index', 
            array(
                    'id' => $payroll->getID(),
                )));
    }
}