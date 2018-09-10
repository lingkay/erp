<?php

namespace Hris\ToolsBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\UserBundle\Utility\ManagerGroupName;
use Hris\ToolsBundle\Entity\Schedule;
use Hris\ToolsBundle\Entity\ScheduleEntry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Gist\ValidationException;
use Hris\WorkforceBundle\Entity\Attendance;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\ToolsBundle\Entity\EmployeeAdvance;
use Hris\ToolsBundle\Entity\EmployeeAdvanceEntry;
use DateTime;
use SplFileObject;
use LimitIterator;

class CashAdvanceController extends CrudController
{
    use TrackCreate;
    protected $date_from;
    protected $date_to;
    public function __construct()
    {
        $this->route_prefix = 'hris_tools_advance';
        $this->title = 'Cash Advance';
        $this->list_title = 'Cash Advance';
        $this->list_type = 'dynamic';
        $this->repo = "HrisToolsBundle:EmployeeAdvance";
    }


    protected function newBaseClass()
    {
        return new EmployeeAdvance();
    }
    
    protected function getObjectLabel($obj)
    {
        if ($obj == null){
            return '';
        }
        return $obj->getID();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('a', 'team', 'getTeam'),
            // $grid->newJoin('g', 'group', 'getGroup'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Employee', 'getEmployeeName', 'employee'),
            $grid->newColumn('Team', 'getName', 'name', 'a'),
            $grid->newColumn('Type', 'getType', 'type'),
            $grid->newColumn('Amount', 'getTotal', 'total'),
            $grid->newColumn('Balance', 'getBalance', 'debit'),
 
        );
    }
    protected function padListParams(&$params, $obj = null)
    {
        $params['date_from'] = $this->date_from->format('m/d/Y'); //$this->date_from->format('m/d/Y'): $date_from->format('m/d/Y');
        $params['date_to'] = $this->date_to->format('m/d/Y');// != null?$this->date_to->format('m/d/Y'): $date_to->format('m/d/Y');
        
        return $params;

    }

    protected function padFormParams(&$params, $user = null)
    {
	    $em = $this->getDoctrine()->getManager();
        $sm = $this->get('hris_settings');
        $um = $this->get('gist_user');

        // $params['adjustment_opts'] = [EmployeeAdjustment::ADJUSTMENT_FINE => EmployeeAdjustment::ADJUSTMENT_FINE,
        //                                 EmployeeAdjustment::ADJUSTMENT_BASIC => EmployeeAdjustment::ADJUSTMENT_BASIC,
        //                                 EmployeeAdjustment::ADJUSTMENT_13TH => EmployeeAdjustment::ADJUSTMENT_13TH,
        //                                 EmployeeAdjustment::ADJUSTMENT_OVERPAID => EmployeeAdjustment::ADJUSTMENT_OVERPAID];
       
        $params['type_opts'] = [EmployeeAdvance::TYPE_STRAIGHT => EmployeeAdvance::TYPE_STRAIGHT,
        						EmployeeAdvance::TYPE_INSTALLMENT => EmployeeAdvance::TYPE_INSTALLMENT];
        $params['emp_opts'] = $um->getUserFullNameOptions();
        $params['cutoff_opts'] = ["A"=>"A", "B"=>"B"];
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $sm = $this->get('hris_settings');
        $um = $this->get('gist_user');


        $employee = $um->findUser($data['employee']);
        $given_by = $um->findUser($data['given']);
        $o->setEmployee($employee)
            ->setGivenBy($given_by)
            ->setTeam($employee->getArea())
            ->setType($data['type'])
            ->setTotal($data['amount'])
            ->setBalance($data['balance'])
            ->setNotes($data['notes'])
            ->setDateRequest(new DateTime($data['date_request']))
            ->setDateRelease(new DateTime($data['date_release']));
        
        foreach ($o->getEntries() as $entry ) {
            $em->remove($entry);
        }
        $o->clearEntries();
        $em->flush();
        $count = 1;
        foreach ($data['deduction_input'] as $index => $value) {
            $entry = new EmployeeAdvanceEntry();
            $entry->setDeduction($value)
                ->setBalance($data['balance_input'][$index])
                ->setCutoff($data['cutoff'][$index])
                ->setCount($count)
                ->setDateDeduction(new DateTime($data['date_deduction'][$index]));

            $o->addEntry($entry);
            $em->persist($entry);
            $count++;
        }

        $this->updateTrackCreate($o, $data, $is_new);
    }


    protected function hookPreAction()
    {
        $this->getControllerBase();
        if($this->getRequest()->get('date_from') != null){
            $this->date_from = new DateTime($this->getRequest()->get('date_from'));
        }else {
           $date_from = new DateTime();
           $date_from->modify('first day of this month');
           $this->date_from = $date_from;
        }

        if($this->getRequest()->get('date_to') != null){
            $this->date_to = new DateTime($this->getRequest()->get('date_to'));
        }else {
           $date_to = new DateTime();
           $date_to->modify('last day of this month');
           $this->date_to = $date_to;
        }
    }

    protected function headers()
    {
        return ["Employee",
                "Employee Code",
                "Team",
                "Straight/Installment",
                "Qty/Installment",
                "Given By",
                "# Installment",
                "Of",
                "Total Amount",
                "Deduction Amount",
                "Balance",
                "Release Date",
                "Request Date",
                "Full Deduction",
                "Month",
                "Cutoff",
                "Year"];
    }

    public function csvAction()
    {
        $this->hookPreAction();

        $csv_headers = $this->headers();
        $date = $this->date_from->format('Ymd')."-".$this->date_to->format('Ymd');
        $filename = 'ADVANCE_'.$date.'.csv';
        $file = fopen('php://output', 'w');
        ob_start();

        fputcsv($file, $csv_headers);
        foreach ($this->getData() as $advance) {
            foreach($advance->getEntries() as $row ){
               $data = [];
               $data[] = $advance->getEmployeeName();
               $data[] = "'".$advance->getEmployee()->getUsername()."'";
               $data[] = $advance->getTeam()->getName();
               $data[] = $advance->getType();
               $data[] = $advance->countEntries();
               $data[] = $advance->getGivenName();
               $data[] = $row->getCount();
               $data[] = $advance->countEntries();
               $data[] = $advance->getTotal();
               $data[] = $row->getDeduction();
               $data[] = $row->getBalance();
               $data[] = $advance->getDateRelease()->format('m/d/Y');
               $data[] = $advance->getDateRequest()!= null ? $advance->getDateRequest()->format('m/d/Y'): "";
               $data[] = $advance->isFullDeduction()? "Y":"N";
               $data[] = $row->getDateDeduction()->format('F');
               $data[] = $row->getCutoff();
               $data[] = $row->getDateDeduction()->format('Y');

               fputcsv($file, $data);
            }
        }
        fclose($file);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);

        return $response;
    }

    protected function getData()
    {
        $em = $this->getDoctrine()->getManager();
      
        $qb = $em->createQueryBuilder();
        $qb->select('o')
            ->from('HrisToolsBundle:EmployeeAdvance', 'o')
            ->where('o.date_release between :date_from and :date_to ')
            ->setParameter('date_from', $this->date_from)
            ->setParameter('date_to', $this->date_to);

        $result = $qb->getQuery()->getResult();
        return $result;
    }


}