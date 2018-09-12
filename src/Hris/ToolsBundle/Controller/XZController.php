<?php

namespace Hris\ToolsBundle\Controller;

use Gist\TemplateBundle\Model\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;

use DateTime;
use SplFileObject;
use LimitIterator;

class XZController extends BaseController
{
    protected $date_from;
    protected $date_to;
    protected $xz;
    protected $data;

    public function __construct()
    {
        $this->route_prefix = 'hris_tools_xz';
        $this->title = 'XZ Report';
        $this->list_title = 'XZ Report';
        $this->list_type = 'dynamic';
        $this->repo = "GistPOSERPBundle:POSTransaction";
    }


    public function indexAction()
    {
        $data = $this->getRequest()->query->all();
       
        $params = $this->getViewParams('List');
        $this->padListParams($params);
        $twig_file = 'HrisToolsBundle:XZ:index.html.twig';
        

        //Sales
        $xz = $this->get('tools_xz');
        // $params['chart'] = $xz->getSalesPerProduct($data);
        // print_r($params['chart']);
        return $this->render($twig_file, $params);
    }

    protected function padListParams(&$params)
    {
        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->modify('-7 Days');
        $params['area_opts'] =  $this->getUserAreas();
        $params['date_from'] = $date_from->format('m/d/Y'); //$this->date_from->format('m/d/Y'): $date_from->format('m/d/Y');
        $params['date_to'] = $date_to->format('m/d/Y');// != null?$this->date_to->format('m/d/Y'): $date_to->format('m/d/Y');
        $params['cdate'] = new DateTime();
       
    }

    protected function getUserAreas()
    {
        $conf = $this->get('gist_configuration');
        $settings = $this->get('hris_settings');
        $am = json_decode($conf->get('tools_area_manager'));
        $admin = json_decode($conf->get('tools_admin'));
        $am = $am==null?[]:$am;
        $admin = $admin==null?[]:$admin;
        $area = $this->getUser()->getArea();
        $locs = $area->getLocations();
       
        if(in_array($this->getUser()->getGroup()->getID() , $admin)){
            $locs = $area->getLocations();
        }

        // print_r($area->); die();
        $opts = [];
        foreach($locs as $entry ){
            $opts[$entry->getID()] = $entry->getName();
        }
        return $opts;
    }
    protected function hookPreAction()
    {
        $this->xz = $this->get('tools_xz');
        $this->data = $this->getRequest()->query->all();
    }

    public function salesAction()
    {
        $this->hookPreAction();
        $chart = $this->xz->getSalesChart($this->data);
        return new JsonResponse($chart->toData());
    }

    public function salesTableAction()
    {
        $this->hookPreAction();
        $table = $this->xz->getSalesTableData($this->data);
        return new JsonResponse($table);
    }


    public function productAction()
    {
        $this->hookPreAction();
        $response = $this->xz->getSalesPerProduct($this->data);
        return new JsonResponse($response);
    }
  
    public function locationAction()
    {
        $this->hookPreAction();
        $response = $this->xz->getSalesPerLocation($this->data);
        return new JsonResponse($response);
    }
  
    public function customerAction()
    {
        $this->hookPreAction();
        $response = $this->xz->getCustomerSales($this->data);
        return new JsonResponse($response);
    }
    
    public function employeeAction()
    {
        $this->hookPreAction();
        $chart = $this->xz->getEmployeeChart($this->data);
      
        return new JsonResponse($chart);
    }

    public function customerPortletAction()
    {
        $this->hookPreAction();
        $chart = $this->xz->getCustomerChart($this->data);
      
        return new JsonResponse($chart);
    }

    public function modeAction()
    {
        $this->hookPreAction();
        $chart = $this->xz->getModeChart($this->data);
      
        return new JsonResponse($chart);
    }

    public function transactionsAction()
    {
        $this->hookPreAction();
        $chart = $this->xz->getTransactionsData($this->data);
      
        return new JsonResponse($chart);
    }

    public function commissionAction()
    {
        $this->hookPreAction();
        $chart = $this->xz->getCommissionData($this->data);
      
        return new JsonResponse($chart);
    }
}