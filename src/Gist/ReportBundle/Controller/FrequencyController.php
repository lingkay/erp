<?php

namespace Gist\ReportBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

class FrequencyController extends CrudController
{
	public function __construct()
    {
        $this->route_prefix = 'gist_report_freq';
        $this->title = 'Product Frequency Report';

        $this->list_title = 'Product Frequency Report';
        $this->list_type = 'static';
    }

    public function indexAction()
    {
    	$this->title = 'Product Frequency Report';
        $params = $this->getViewParams('', 'gist_report_prodfreq_summary');
        $inv = $this->get('gist_inventory');

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;

        $params['br_opts'] = $inv->getBranchOptions();
        $params['item_opts'] = $inv->getProductOptions();
        $params['prod_opts'] = $inv->getProductGroupOptions();        

        return $this->render('GistReportBundle:Frequency:index.html.twig', $params);
    }

    protected function getObjectLabel($object) {
        
    }

    protected function newBaseClass() {
        
    }
}