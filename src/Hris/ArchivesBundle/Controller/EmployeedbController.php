<?php

namespace Hris\ArchivesBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class EmployeedbController extends CrudController
{
	public function __construct()
	{
		$this->route_prefix = 'hris_employeedb';
		$this->title = 'Employee';

		$this->list_title = 'Employee Database';
		$this->list_type = 'dynamic';
	}

	public function indexAction()
	{
		$this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_employeedb_index');

        $twig_file = 'HrisArchivesBundle:Employeedb:index.html.twig';

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();
        $params['title'] = $this->title;

        return $this->render($twig_file, $params);		
	}

	protected function getObjectLabel($object) {
        
    }

    protected function newBaseClass() {
        
    }

    public function addSubmitAction(){
        
    }

    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');
        return array(
            $grid->newColumn('Name', '', ''),
            $grid->newColumn('Department', '', ''),
            $grid->newColumn('Current Position', '', ''),
            $grid->newColumn('Status', '', ''),            
        );
    }
}