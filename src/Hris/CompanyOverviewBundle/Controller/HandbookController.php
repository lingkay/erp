<?php

namespace Hris\CompanyOverviewBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class HandbookController extends CrudController
{
	public function __construct()
	{
		$this->route_prefix = 'hris_com_handbook';
		$this->title = 'Handbook';

		$this->list_title = 'Handbook';
		$this->list_type = 'static';
	}

	public function indexAction()
	{

		$this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_com_handbook_index');

        $twig_file = 'HrisCompanyOverviewBundle:Handbook:index.html.twig';

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
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Title', '', ''),
        );
    } 
}