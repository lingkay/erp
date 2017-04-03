<?php

namespace Hris\WorkforceBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class BenefitController extends CrudController
{
	public function __construct()
	{
		$this->route_prefix = 'hris_workforce_benefits';
		$this->title = 'Benefit';

		$this->list_title = 'Benefits';
		$this->list_type = 'dynamic';
	}

	public function indexAction()
	{
		$this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_workforce_benefits_index');

        $twig_file = 'HrisWorkforceBundle:Benefit:index.html.twig';

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();
        $params['title'] = $this->title;

        return $this->render($twig_file, $params);			
	}

	protected function getObjectLabel($object) 
    {
        
    }

    protected function newBaseClass()
    {
        
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Benefit', '', ''),
            $grid->newColumn('Description', '', ''),
            $grid->newColumn('Bond Requirement', '', ''),                    
        );
    }    
}