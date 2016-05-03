<?php

namespace Hris\RecruitmentBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use DateTime;

class ChecklistController extends CrudController
{
	public function __construct()
	{
		$this->route_prefix = 'hris_checklist';
		$this->title = 'Pre-employement Checklist';

		// $this->list_title = 'Applications/Interviews';
        $this->list_title = 'Checklist';
		$this->list_type = 'dynamic';
	}

	public function indexAction()
	{
		$this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_checklist_index');
        $date = new DateTime();
        $date->format("Y-m-d");

        $this->padFormParams($params, $date);

        $twig_file = 'HrisRecruitmentBundle:Checklist:index.html.twig';

        $params['date'] = $date;
        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();
        $params['title'] = $this->title;

        return $this->render($twig_file, $params);
	}

	protected function getObjectLabel($object) {
        
    }

    protected function newBaseClass() {
        
    }

    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');
        return array(
            $grid->newColumn('Name of Applicant', '', ''),
            $grid->newColumn('Job Title', '', ''),
            $grid->newColumn('Date of Hire', '', ''),
            $grid->newColumn('Status', '', ''),
        );
    }
    
    public function printAction()
    {
        $gl = $this->setupGridLoader();
        $this->title = $this->title;
        $params = $this->getViewParams('', $this->route_prefix.'_index');

        $params['grid_cols'] = $gl->getColumns();
        $params['data'] = $gl->getColumns();

        return $this->render(
            'HrisRecruitmentBundle:Checklist:print.html.twig', $params);
    }   
    
}