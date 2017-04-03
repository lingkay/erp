<?php

namespace Hris\DashboardBundle\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gist\TemplateBundle\Model\CrudController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Hris\RecruitmentBundle\Entity\ManpowerRequest;

class JobVacancyController extends Controller
{
	public function __construct()
	{
		$this->route_prefix = 'hris_dashboard_job_vacancy';
		$this->title = 'Job Vacancy';

		$this->list_title = 'Job Vacancy';
		$this->list_type = 'dynamic';
        $this->repo = 'HrisRecruitmentBundle:ManpowerRequest';
	}

	public function indexAction()
	{
		$params = $this->getViewParams('view', 'hris_dashboard_job_vacancy');
        $sample = $this->getUser()->getGroups();
        
		$gl = $this->setupGridVacancy();

		$params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

		return $this->render('HrisDashboardBundle:Dashboard:jobvacancy.html.twig', $params);
	}

	protected function getObjectLabel($object) {
        
        if ($obj == null){
            return '';
        }
        return $obj->getCode();
    }

    protected function newBaseClass() {
        
    }

    protected function getGridVacancy()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Position Title', 'getName', 'name', 'p'),
            $grid->newColumn('Department', 'getName','name', 'd'),
            $grid->newColumn('Number of Vacant Position', 'getVacancy', 'vacancy'),  
        );
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('d', 'department', 'getDepartment'),
            $grid->newJoin('p', 'position', 'getPosition')
        );
    }

    protected function setupGridVacancy()
    {
        $grid = $this->get('gist_grid');
        $data = $this->getRequest()->query->all();
        $em = $this->getDoctrine()->getManager();

        // setup grid
        $gl = $grid->newLoader();
        $gl->processParams($data)
            ->setRepository('HrisRecruitmentBundle:ManpowerRequest')
            ->enableCountFilter();

        // joins
        $gjoins = $this->getGridJoins();
        foreach ($gjoins as $gj)
            $gl->addJoin($gj);

        // columns
        $stock_cols = $this->getGridVacancy();

        // add action column if it's dynamic
        if ($this->list_type == 'dynamic')
            $stock_cols[] = $grid->newColumn('', 'getID', null, 'o', array($this, 'callbackGrid'), false, false);

        foreach ($stock_cols as $col)
            $gl->addColumn($col);

        return $gl;
    }

    public function callbackGrid($id)
    {
        $params = array(
            'id' => $id,
            'route_edit' => 'hris_requisition_edit_form',
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'HrisDashboardBundle::action.html.twig',
            $params
        );
    }

    public function gridVacancyAction()
    {
        $gl = $this->setupGridVacancy();
        $qry = array();

        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();
        $qry[] = "(o.status = 'Approved')";
        
        // print_r($qry);
        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
            $fg->where($filter);
            $gl->setQBFilterGroup($fg);
        }

        $gres = $gl->load();

        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }
}