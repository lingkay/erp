<?php

namespace Gist\ReportBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

class InsufficientController extends CrudController
{
	public function __construct()
	{
		$this->route_prefix = 'gist_report_insufficient';
        $this->title = 'Insufficient Stock Report';

        $this->list_title = 'Insufficient Stock Report';
        $this->list_type = 'static';
	}

	public function indexAction()
	{
		$this->title = 'Insufficient Stock Report';
        $params = $this->getViewParams('', 'gist_report_insufficient_summary');
        $inv = $this->get('gist_inventory');

        $params['br_opts'] = $inv->getBranchOptions();
        $params['prod_opts'] = $inv->getProductGroupOptions();

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");


        $this->padFormParams($params, $date_from, $date_to);

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;

        
        return $this->render('GistReportBundle:Insufficient:index.html.twig', $params);
	}

	protected function getObjectLabel($object) {
        
    }

    protected function newBaseClass() {
        
    }
}