<?php

namespace Hris\TrainingBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use DateTime;

class EtrainController extends CrudController
{
	public function __construct()
	{
		$this->route_prefix = 'hris_etrain';
		$this->title = 'Training';

		$this->list_title = 'Training';
		$this->list_type = 'dynamic';
	}

	public function indexAction()
	{
		$this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_etrain_index');

        $twig_file = 'HrisTrainingBundle:Etrain:index.html.twig';

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;

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
            $grid->newColumn('Session Name', '', ''),
            $grid->newColumn('Training Course', '', ''),
            $grid->newColumn('Duration Date', '', ''),
            $grid->newColumn('Status', '', ''),
        );
    }
}