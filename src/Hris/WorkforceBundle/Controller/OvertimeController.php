<?php

namespace Hris\WorkforceBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

use Hris\RecruitmentBundle\Entity\Application;
use Hris\WorkforceBundle\Entity\Attendance;

class OvertimeController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'hris_workforce_overtime';
        $this->title = 'Overtime';

        $this->list_title = 'Overtime';
        $this->list_type = 'dynamic';
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List');

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        $em = $this->getDoctrine()->getManager();
        $data = array();

        $overtimes = $em->getRepository('HrisWorkforceBundle:Attendance')->findAll();

        foreach($overtimes as $overtime)
        {
            if($overtime->getOvertimeStatus() == Attendance::STATUS_DRAFT)
            {
                $data[] = [
                    'id' => $overtime->getID(),
                    'date' => $overtime->getOvertimeDate()->format('m/d/Y'),
                    'name' => $overtime->getEmployee()->getDisplayName(),
                    'hours' => $overtime->getOvertimeTemp(),
                ];
            }
        }

        $params['data'] = $data;

        return $this->render('HrisWorkforceBundle:Overtime:index.html.twig', $params);
    }

    protected function getObjectLabel($object) {
        
    }

    protected function newBaseClass() {
        
    }
}