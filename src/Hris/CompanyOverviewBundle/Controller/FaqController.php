<?php

namespace Hris\CompanyOverviewBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class FaqController extends CrudController
{
	public function __construct()
	{
		$this->route_prefix = 'hris_faq';
		$this->title = 'FAQ';

		$this->list_title = 'FAQ';
		$this->list_type = 'dynamic';
	}

	public function indexAction()
	{
		$this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_faq_index');

        $twig_file = 'HrisCompanyOverviewBundle:Faq:index.html.twig';

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();
        $params['title'] = $this->title;

        return $this->render($twig_file, $params);	
	}

	protected function getObjectLabel($obj) {
        
        if ($obj == null){
            return '';
        }
        return $obj->getCode();
        
    }

    protected function newBaseClass() {
        
    }

    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');
        return array(
            $grid->newColumn('Title', '', ''),
            $grid->newColumn('Description', '', ''),                        
        );
    }
}