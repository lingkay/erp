<?php

namespace Hris\DashboardBundle\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gist\TemplateBundle\Model\CrudController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class SchedulesController extends Controller
{
	public function __construct()
	{
		$this->route_prefix = 'hris_dashboard_schedules';
		$this->title = 'Schedules';

		$this->list_title = 'Schedules';
		$this->list_type = 'dynamic';
	}

	public function indexAction()
	{

		$params = $this->getViewParams('', 'hris_dashboard_schedules');
        $sample = $this->getUser()->getGroups();
        
		$gl = $this->setupGridLoader();

		$params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

		return $this->render('HrisDashboardBundle:Dashboard:schedules.html.twig', $params);
	}

	protected function getObjectLabel($object) {
        
        if ($obj == null){
            return '';
        }
        return $obj->getCode();
    }

    protected function newBaseClass() {
        
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Time', '', ''),
            $grid->newColumn('Date', '', ''),
            $grid->newColumn('Name of Applicant', '', ''),
            $grid->newColumn('Job Position', '', ''),
            $grid->newColumn('Type of Schedule', '', ''),                       
        );
    }
   
}