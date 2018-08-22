<?php

namespace Hris\ToolsBundle\Controller;

use Gist\TemplateBundle\Model\BaseController;
use DateTime;
use SplFileObject;
use LimitIterator;

class XZController extends BaseController
{
    protected $date_from;
    protected $date_to;

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
        $params['chart'] = $xz->getSalesChart($data);
        
        return $this->render($twig_file, $params);
    }

    protected function padListParams(&$params)
    {

    }

    public function getTotalSalesAction()
    {
        $xz = $this->get('tools_xz');

        $params['chart'] = $xz->getChart($data);
    }


  
    


}