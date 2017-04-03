<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\BaseController;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

use DateTime;

class SettingController extends BaseController
{

    public function overtimeIndexAction()
    {
        $this->title = 'Overtime Settings';
        $params = $this->getViewParams('', 'hris_admin_otsetting_index');
        $settings = $this->get('hris_settings');
        $conf = $this->get('gist_configuration');

       
        $params['job_level'] = $settings->getJobLevelOptions();
        $params['emp_type'] = $settings->getEmploymentStatusOptions();
        $params['ot_allowed'] = json_decode($conf->get('hris_setting_overtime_groups'), true);
        $params['threshold'] = $conf->get('hris_setting_overtime_threshold');

        return $this->render('HrisAdminBundle:Settings:overtime.html.twig', $params);
    }


    public function overtimeSubmitAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();
        $conf = $this->get('gist_configuration');


        $conf->set('hris_setting_overtime_threshold', $data['threshold']);
        $conf->set('hris_setting_overtime_groups', json_encode($data['ot_allowed']));
    
        $em->flush();

        return $this->redirect($this->generateUrl('hris_admin_otsetting_index'));
    }

     public function semimonthlyIndexAction()
    {
        $this->title = 'Semi Monthly Settings';
        $params = $this->getViewParams('', 'hris_payroll_semimonthly_index');
        $payroll = $this->get('hris_payroll');
        $conf = $this->get('gist_configuration');

        $params['percent_opts'] = [ '0'=>'0%','25'=>'25%', '50'=>'50%', '75'=>'75%', '100'=>'100%'];
        $params['dates_opts'] = [ 'End of the month',1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31];
      
        $params['sched'] = json_decode($conf->get('hris_payroll_semimonthly_sched'));
        $params['payroll_percent'] = json_decode($conf->get('hris_payroll_semimonthly_payroll_percent'));
        $params['sss'] =$conf->get('hris_payroll_semimonthly_payroll_sss');
        $params['pagibig'] = $conf->get('hris_payroll_semimonthly_payroll_pagibig');
        $params['philhealth'] = $conf->get('hris_payroll_semimonthly_payroll_philhealth');

        return $this->render('HrisPayrollBundle:Settings:semimonthly.html.twig', $params);
    }


    public function semimonthlySubmitAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();
        $conf = $this->get('gist_configuration');


        $conf->set('hris_payroll_semimonthly_sched', json_encode($data['sched']));
        $conf->set('hris_payroll_semimonthly_payroll_percent', json_encode($data['payroll']['percent']));
        $conf->set('hris_payroll_semimonthly_payroll_sss', $data['payroll']['sss']);
        $conf->set('hris_payroll_semimonthly_payroll_philhealth', $data['payroll']['philhealth']);
        $conf->set('hris_payroll_semimonthly_payroll_pagibig', $data['payroll']['pagibig']);
        $em->flush();

        return $this->redirect($this->generateUrl('hris_payroll_semimonthly_index'));
    }
}