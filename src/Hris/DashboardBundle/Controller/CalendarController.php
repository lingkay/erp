<?php

namespace Hris\DashboardBundle\Controller;

use Gist\TemplateBundle\Model\CrudController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->route_prefix = 'hris_dashboard_calendar';
        $this->title = 'Calendar';

        $this->list_title = 'Calendar';
        // $this->list_type = 'dynamic';
    }

    public function indexAction()
    {

        $params = $this->getViewParams('settings', 'hris_dashboard_calendar');
        $sample = $this->getUser()->getGroups();
        
        $gl = $this->setupGridLoader();

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        return $this->render('HrisDashboardBundle:Dashboard:calendar.html.twig', $params);
    }

    protected function getObjectLabel($object) {
        
        // if ($obj == null){
        //     return '';
        // }
        // return $obj->getCode();
    }

    protected function newBaseClass() {
        
    }
   
}