<?php

namespace Hris\PayrollBundle\Controller;

use Gist\TemplateBundle\Model\BaseController;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

use Hris\PayrollBundle\Entity\PaySchedule;


use DateTime;

class PaySettingsController extends BaseController
{

    public function weeklyIndexAction()
    {
        $this->title = 'Weekly Settings';
        $params = $this->getViewParams('', 'hris_payroll_weekly_index');
        $payroll = $this->get('hris_payroll');
        $conf = $this->get('gist_configuration');

        $params['days_opts'] = [ '1'=>'Monday', '2'=>'Tuesday', '3'=>'Wednesday', '4'=>'Thursday', '5'=>'Friday', '6'=>'Saturday', '7'=>'Sunday'];
        $params['percent_opts'] = [ '0'=>'0%','25'=>'25%', '50'=>'50%', '75'=>'75%', '100'=>'100%'];

        $params['sched'] = json_decode($conf->get('hris_payroll_weekly_sched'));
        $params['payroll_percent'] = json_decode($conf->get('hris_payroll_weekly_payroll_percent'));
        $params['sss'] =$conf->get('hris_payroll_weekly_payroll_sss');
        $params['pagibig'] = $conf->get('hris_payroll_weekly_payroll_pagibig');
        $params['philhealth'] = $conf->get('hris_payroll_weekly_payroll_philhealth');

        return $this->render('HrisPayrollBundle:Settings:weekly.html.twig', $params);
    }


    public function weeklySubmitAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();
        $conf = $this->get('gist_configuration');
        $pay = $this->get('hris_payroll');

        error_log(print_r($data,true));
        $conf->set('hris_payroll_weekly_sched', json_encode($data['sched']));
        // $conf->set('hris_payroll_weekly_payroll_percent', json_encode($data['payroll']['percent']));
        // $conf->set('hris_payroll_weekly_payroll_sss', $data['payroll']['sss']);
        // $conf->set('hris_payroll_weekly_payroll_philhealth', $data['payroll']['philhealth']);
        // $conf->set('hris_payroll_weekly_payroll_pagibig', $data['payroll']['pagibig']);
        $em->flush();
        // if(isset($data['generate'])){
        //     $pay->generateWeeklyPayPeriod();
        // }

        return $this->redirect($this->generateUrl('hris_payroll_weekly_index'));
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
        //$conf->set('hris_payroll_semimonthly_payroll_percent', json_encode($data['payroll']['percent']));
        $conf->set('hris_payroll_semimonthly_payroll_sss', $data['payroll']['sss']);
        $conf->set('hris_payroll_semimonthly_payroll_philhealth', $data['payroll']['philhealth']);
        $conf->set('hris_payroll_semimonthly_payroll_pagibig', $data['payroll']['pagibig']);
        $em->flush();

        return $this->redirect($this->generateUrl('hris_payroll_semimonthly_index'));
    }

    public function minIndexAction()
    {
        $this->title = 'Minimum Wage Settings';
        $params = $this->getViewParams('', 'hris_payroll_setting_min_index');
        $payroll = $this->get('hris_payroll');
        $conf = $this->get('gist_configuration');

        $params['min'] = $conf->get('hris_payroll_min_wage');
        $params['min_agri'] = $conf->get('hris_payroll_min_agri');
        $params['ecola'] = $conf->get('hris_payroll_min_ecola');
    
        return $this->render('HrisPayrollBundle:Settings:min.html.twig', $params);
    }


    public function minSubmitAction()
    {
        $this->checkAccess('hris_payroll_setting_min_index.edit');

        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();
        $conf = $this->get('gist_configuration');


        $conf->set('hris_payroll_min_wage', $data['min']);
        $conf->set('hris_payroll_min_agri', $data['min_agri']);
        $conf->set('hris_payroll_min_ecola', $data['ecola']);
        $em->flush();

        return $this->redirect($this->generateUrl('hris_payroll_setting_min_index'));
    }

    public function yearIndexAction()
    {
        $this->title = 'Schedule Settings';
        $params = $this->getViewParams('', 'hris_payroll_setting_year_index');
        $payroll = $this->get('hris_payroll');
        $conf = $this->get('gist_configuration');

        $params['week_start'] = $conf->get('hris_payroll_weekly_year_start');
        $params['week_end'] = $conf->get('hris_payroll_weekly_year_end');
        $params['semi_start'] = $conf->get('hris_payroll_semi_year_start');
        $params['semi_end'] = $conf->get('hris_payroll_semi_year_end');
        return $this->render('HrisPayrollBundle:Settings:year.html.twig', $params);
    }

    public function yearSubmitAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();
        $conf = $this->get('gist_configuration');
        $pm = $this->get('hris_payroll');

        $conf->set('hris_payroll_weekly_year_start', $data['week_start']);
        $conf->set('hris_payroll_weekly_year_end', $data['week_end']);
        $conf->set('hris_payroll_semi_year_start', $data['semi_start']);
        $conf->set('hris_payroll_semi_year_end', $data['semi_end']);
        $em->flush();
        
        if(isset($data['generate'])){
            $pm->generatePayPeriods();
        }

        return $this->redirect($this->generateUrl('hris_payroll_setting_year_index'));
    }
}