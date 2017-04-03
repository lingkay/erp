<?php

namespace Hris\DashboardBundle\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gist\TemplateBundle\Model\CrudController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

use Hris\RecruitmentBundle\Entity\Application;

class ShortlistedApplicantController extends Controller
{
	public function __construct()
	{
		$this->route_prefix = 'hris_dashboard_shortlisted_applicant';
		$this->title = 'Shortlisted Applicant';

		$this->list_title = 'Shortlisted Applicant';
		$this->list_type = 'dynamic';
	}

	public function indexAction()
	{

		$params = $this->getViewParams('', 'hris_dashboard_shortlisted_applicant');
        $sample = $this->getUser()->getGroups();
        
		$gl = $this->setupGridLoader();

		$params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        $em = $this->getDoctrine()->getManager();
        $data = array();

        $applications = $em->getRepository('HrisRecruitmentBundle:Application')->findAll();

        foreach($applications as $application)
        {
            if($application->getStatus() == Application::STATUS_OFFER)
            {
                $data[] = [
                    'id' => $application->getID(),
                    'date' => $application->getDateCreate()->format('m/d/Y'),
                    'name' => $application->getDisplayName(),
                    'position' => $application->getChoice(),
                    'status' => $application->getApplicationStatus()
                ];
            }
        }

        $params['data'] = $data;

		return $this->render('HrisDashboardBundle:Dashboard:shortlistedapplicant.html.twig', $params);
	}

	protected function getObjectLabel($object) {
        
    }

    protected function newBaseClass() {
        
    }
}