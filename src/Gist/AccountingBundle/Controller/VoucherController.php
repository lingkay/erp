<?php

namespace Gist\AccountingBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Gist\ValidationException;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\AccountingBundle\Entity\CDJJournalEntry;
use Gist\AccountingBundle\Entity\CDJTransaction;
use Gist\AccountingBundle\Entity\VoucherChecks;
use DateTime;
use SplFileObject;
use LimitIterator;

class VoucherController extends CrudController
{
    use TrackCreate;

    protected $date_from;
    protected $date_to;

    public function __construct()
    {
        $this->route_prefix = 'gist_accounting_voucher';
        $this->title = 'Payment Voucher';
        $this->list_title = 'Payment Voucher';
        $this->list_type = 'dynamic';
        $this->repo = "GistAccountingBundle:CDJTransaction";
    }


    protected function newBaseClass()
    {
        return new CDJTransaction();
    }
    
    protected function getObjectLabel($obj)
    {
        if ($obj == null){
            return '';
        }
        return $obj->getCode();
    }

    // protected function getGridJoins()
    // {
    //     $grid = $this->get('gist_grid');
    //     return array(
    //         $grid->newJoin('a', 'chart_of_account', 'getAccount'),
    //         $grid->newJoin('t', 'transaction', 'getTransaction'),
    //         // $grid->newJoin('g', 'group', 'getGroup'),
    //     );
    // }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Transaction Code', 'getCode', 'code'),
            $grid->newColumn('Record Date', 'getRecordDate', 'record_date', 'o', [$this,'formatDate']),
     
        );
    }

   
     protected function padListParams(&$params, $obj = null)
    {
        $params['date_from'] = $this->date_from->format('m/d/Y'); //$this->date_from->format('m/d/Y'): $date_from->format('m/d/Y');
        $params['date_to'] = $this->date_to->format('m/d/Y');// != null?$this->date_to->format('m/d/Y'): $date_to->format('m/d/Y');
        
        return $params;

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

    protected function filterGrid()
    {
        $this->date_from->setTime(0,0);
        $this->date_to->setTime(23,59);

        $fg = parent::filterGrid();
        $fg->where('o.record_date between :date_from and :date_to ')
            ->setParameter("date_from", $this->date_from)
            ->setParameter("date_to", $this->date_to);
     
        return $fg;
    }

    protected function padFormParams(&$params, $obj = null)
    {
        $um = $this->get('gist_user');
        $im = $this->get('gist_inventory');
        $am = $this->get('gist_accounting');
        $params['obj'] = $obj;
        $params['paytype_opts'] = ["Cash"=>"Cash", "Check" => "Check"];
        $params['emp_opts'] = $um->getUserFullNameOptions();
        $params['supplier_opts'] = $im->getSupplierOptions();
        $params['bank_opts'] = $am->getBankAccountOptions();

        if($obj->getStatus() === CDJTransaction::STATUS_SAVED){
            $params['readonly'] = true;
        }
   
    }

    // public function callbackGrid($id)
    // {
    //     $params = array(
    //         'id' => $id,
    //         'route_edit' => $this->getRouteGen()->getEdit(),
    //         'route_delete' => $this->getRouteGen()->getDelete(),
    //         'prefix' => $this->route_prefix,
    //     );

    //     $this->padGridParams($params, $id);

    //     $twig_file = $this->base_view.':action.html.twig';

    //     return $engine->render(
    //         $twig_file,
    //         $params
    //     );
    // }

    protected function update($obj, $data)
    {

        $em = $this->getDoctrine()->getManager();
        $am = $this->get('gist_accounting');
        $um = $this->get('gist_user');
     
        $obj->setPayee($data['payee'])
            ->setPayeeText($data['payee_text'])
            ->setPaymentType($data['payment_type'])
            // ->setCheckNumber($data['check_number'])
            ->setBank($data['bank'])
            ->setBankText($data['bank_text'])
            ->setStatus(CDJTransaction::STATUS_SAVED)
            ->setCertifiedBy($um->findUser($data['certified_by']))
            ->setApprovedBy($um->findUser($data['approved_by']));

        
          foreach ($obj->getChecks() as $cm) {
                $em->remove($cm);
            }
            $obj->clearChecks();

        foreach ($data['check_number'] as $key => $value) {
            $check_number = $value;
            $check_date = new DateTime($data['check_date'][$key]);
            $amount = $data['check_amount'][$key];
           
            $checks = new VoucherChecks($check_number,$check_date,$amount);
            $em->persist($checks);
            $em->flush();
            $obj->addCheck($checks);

        }

        foreach ($obj->getTempEntries() as $temp) {
            $cdj_entry = new CDJJournalEntry();
            $cdj_entry->setAccount($temp->getAccount())
                ->setDebit($temp->getDebit())
                ->setCredit($temp->getCredit())
                ->setNotes($temp->getNotes())
                ->setUserCreate($this->getUser())
                ->setRecordDate($temp->getRecordDate())
                ->setStatus($temp->getStatus())
                ->setTransaction($obj);

            $em->persist($cdj_entry);
            $em->flush();
            
            $am->addTrialBalance($cdj_entry);
    
        }


        $em->persist($obj);
        $em->flush();
    }



    public function pdfAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->get('gist_user');
        $data = $this->getRequest()->request->all();
        $obj = $em->getRepository('GistAccountingBundle:CDJTransaction')->find($id);

        // $params = $this->getViewParams('Edit');
      
     
        // $obj->setPayee($data['payee_text'])
        //     ->setPaymentType($data['payment_type'])
        //     ->setCheckNumber($data['check_number'])
        //     ->setBank($data['bank_text'])
        //     ->setCertifiedBy($um->findUser($data['certified_by']))
        //     ->setApprovedBy($um->findUser($data['approved_by']));

        // $em->persist($obj);
        // $em->flush();
        
        $this->padFormParams($params, $obj);

        $twig = "GistAccountingBundle:Voucher:print.html.twig";

        $pdf = $this->get('gist_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
  
    }




}