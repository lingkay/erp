<?php

namespace Hris\WorkforceBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Hris\WorkforceBundle\Entity\Employee;


use DateTime;

class OnboardController extends CrudController
{

	public function __construct()
	{
		$this->route_prefix = 'hris_workforce_onboard';
		$this->title = 'New Employees';
        $this->repo = 'HrisWorkforceBundle:Employee';
		$this->list_title = 'New Employees';
		$this->list_type = 'dynamic';
	}
    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List');

        $twig_file = 'HrisWorkforceBundle:Onboard:index.html.twig';
        $this->listStatic($params);
        
        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        return $this->render($twig_file, $params);
    }

    protected function newBaseClass() {
        return new Employee();
    }


    public function callbackGrid($id)
    {
        $params = array(
            'id' => $id,
            'route_edit' => $this->getRouteGen()->getEdit(),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);
        $engine = $this->get('templating');
        return $engine->render(
            'HrisWorkforceBundle:Onboard:action.html.twig',
            $params
        );
    }

    protected function getObjectLabel($obj) 
    {
        if ($obj == null){
            return '';
        }
        return $obj->getDisplayName();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newJoin('j', 'job_title', 'getJobTitle'),
            $grid->newJoin('d', 'department', 'getDepartment'),
            $grid->newJoin('e', 'supervisor', 'getSupervisor','left'),
        );

    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Employee Name', 'getDisplayName', 'last_name'),
            $grid->newColumn('Job Title', 'getName', 'name','j'),
            $grid->newColumn('Employment Status', 'getEmploymentStatus','employment_status'),
            $grid->newColumn('Department', 'getName', 'name','d'),
            $grid->newColumn('Immediate Supervisor', 'getDisplayName', 'last_name','e'),
        );
    }


    protected function padFormParams(&$params, $object = NULL){

       return $params;
    }

}