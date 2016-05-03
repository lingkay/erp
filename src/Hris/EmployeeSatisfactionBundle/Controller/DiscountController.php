<?php

namespace Hris\EmployeeSatisfactionBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class DiscountController extends CrudController
{
	public function __construct()
	{
		$this->route_prefix = 'hris_discounts';
		$this->title = 'Discounts and Perks';

		$this->list_title = 'Discount and Perks';
		$this->list_type = 'dynamic';
	}

	public function indexAction()
	{
		$this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_discounts_index');

        $twig_file = 'HrisEmployeeSatisfactionBundle:Discount:index.html.twig';

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
            $grid->newColumn('Discounts and Perks', '', ''),
            $grid->newColumn('Details', '', ''),
        );
    }
}