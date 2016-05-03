<?php

namespace Hris\DashboardBundle\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Catalyst\TemplateBundle\Model\BaseController as Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
    	$this->title = 'Dashboard';
        $params = $this->getViewParams('', 'hris_dashboard_index');
        $settings = $this->get('hris_settings');
        $recruitment = $this->get('hris_recruitment');
        $request = $this->get('hris_request');
        $params['dept_count'] = $settings->getDepartmentHeadcount();
        $params['regular_count'] = $settings->getRegularCount();
        $params['contractual_count'] = $settings->getContractualCount();
        $params['probation_count'] = $settings->getProbationCount();
        $params['resigned_count'] = $settings->getResignedCount();
        $params['employee_count'] = ($params['probation_count'] + $params['contractual_count'] + $params['regular_count']);

        $params['shortlisted_count'] = $recruitment->getShortlistedCount();
        $params['vacancy_count'] = $recruitment->getVacancyCount();
        $params['request_count'] = $request->getRequestCount();

        return $this->render('HrisDashboardBundle:Dashboard:index.html.twig', $params);
    }
}
