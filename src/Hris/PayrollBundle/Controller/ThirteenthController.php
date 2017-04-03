<?php

namespace Hris\PayrollBundle\Controller;

use Gist\TemplateBundle\Model\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;

use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;

use Hris\PayrollBundle\Entity\PayTax;

use DateTime;

class ThirteenthController extends BaseController
{
    public function __construct()
    {
        $this->route_prefix = 'hris_payroll_generate';
        $this->title = '13th Month';

        $this->list_title = '13th Month';
        $this->list_type = 'dynamic';
    }

    public function indexAction()
    {
        $conf = $this->get('gist_configuration');

        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();
        

        $params = $this->getViewParams('', 'hris_payroll_thirteenth_index');
        $this->padFormParams($params);
        $params['payroll'] = [];
        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $this->getGridColumns();
        $params['title'] = $this->title;

        $start = new Datetime('January 1');
        $end = new Datetime('December 31');

        $params['year_start'] = $start->format('m/d/Y');
        $params['year_end'] = $end->format('m/d/Y');

        $year = $end->format('Y');
        $yearless = (integer)$year - 1;
        $params['year_opts'] = [$year =>$year, $yearless => $yearless];
        $params['year'] = $year;
        $twig_file = 'HrisPayrollBundle:Thirteenth:index.html.twig';
   
        return $this->render($twig_file, $params); 
        
    }

    public function filterAction()
    {
        $data = $this->getRequest()->request->all();

        
        $payroll = $this->get('hris_payroll_compute');
        $p13th = $this->get('hris_thirteenth');
        $pm = $this->get('hris_payroll');
        $settings = $this->get('hris_settings');
        $conf = $this->get('gist_configuration');
        $params = $this->getViewParams('', 'hris_payroll_thirteenth_index');
      
        $start = new Datetime($data['year_start']);
        $end = new Datetime($data['year_end']);
        $year = $data['year'];
        $yearless = (integer)$year - 1;
        $params['year_opts'] = [$year =>$year, $yearless => $yearless];
   

        $employees = $p13th->generate13th($start, $end, $year);

        $this->padFormParams($params);
        $params['payroll'] = $employees;
        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $this->getGridColumns();
        $params['title'] = $this->title;
        $params['year_start'] = $start->format('m/d/Y');
        $params['year_end'] = $end->format('m/d/Y');
        $params['year'] = $year;
   
        $twig_file = 'HrisPayrollBundle:Thirteenth:index.html.twig';
        //$this->notify($date_from, $date_to, $sched);
        return $this->render($twig_file, $params);      
    }

    protected function notify($date_from, $date_to, $period)
    {
            $config = $this->get('gist_configuration');
            $settings = $this->get('hris_settings');
            $hr = $settings->getDepartment($config->get('hris_hr_department'));

            $msg = sprintf("Payroll Generated for %s employees for period %s to %s. ", 
                    $period->getName(),
                    $date_from->format('m/d/Y'),
                    $date_to->format('m/d/Y'));

            $event = new NotificationEvent();
            $event->notify(array(
                'source'=> 'Payroll Generated',
                'link'=> $this->generateUrl('hris_payroll_view_index'),
                'message'=> $msg,
                'type'=> Notification::TYPE_UPDATE,
                'receipient' => $hr));

                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch('notification.event', $event);
    }

    protected function getObjectLabel($object) {
        
    }

    protected function newBaseClass() {
        return new PayPayroll();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Name', '', ''),
            $grid->newColumn('Gross Pay', '', ''),
            $grid->newColumn('Net Pay', '', ''),
        );
    }

    // public function update($o, $data, $is_new = false)
    // {
    //     $wfm = $this->get('hris_workforce');
    //     $pay = $this->get('hris_payroll');
    // }

    protected function padFormParams(&$params, $object = NULL)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;

        $settings = $this->get('hris_settings');
        $payroll = $this->get('hris_payroll');

        $params['dept_opts'] = $settings->getDepartmentOptions();
        $params['sched_opts'] = $payroll->getPaySchedPayrollOptions();
        return $params;
    }

    public function detailsAction($id)
    {
        $params = $this->padDetailsParam($id);
        $twig_file = 'HrisPayrollBundle:Thirteenth:details.html.twig';
  
        return $this->render($twig_file, $params);
    }


    public function printAction($id)
    {
       
        $twig = "HrisPayrollBundle:Thirteenth:pdf.html.twig";
        $em = $this->getDoctrine()->getManager();
        $conf = $this->get('gist_configuration');
        $media = $this->get('gist_media');

        $params = $this->padDetailsParam($id);
        // $params['company_name'] = strtoupper($conf->get('hris_com_info_company_name'));
        // $params['company_website'] = $conf->get('hris_com_info_website');
        // $params['company_address'] = $em->getRepository('GistContactBundle:Address')->find($conf->get('hris_com_info_company_address'));
        
        if ($conf->get('hris_com_logo') != '') 
        {
            $path = $media->getUpload($conf->get('hris_com_logo'));

            $str = $path->getURL();
            $str = parse_url($str, PHP_URL_PATH);
            $str = ltrim($str, '/');

            $params['logo'] = $str;
        }
        else
        {
            $params['logo'] = '';
        }


        $pdf = $this->get('gist_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
   
    }

    protected function padDetailsParam($id)
    {
         $payroll = $this->get('hris_payroll_compute');
        $pm = $this->get('hris_thirteenth');
        
        $params = $this->getViewParams('List');
        $params['payroll'] = $pm->get13thMonthDetails($id);
      

        return $params;
    }

    // protected function parseDay($day)
    // {
    //     $now = new Datetime();
    //     if($day == 0){
    //          return $now->setDate($now->format('Y'),$now->format('m'), $now->format('t')  );
    //     }

    //     if($day > (integer) $now->format('d')){
    //         return $now->setDate($now->format('Y'),$now->format('m') - 1, $day );
    //     }else {
    //         return $now->setDate($now->format('Y'),$now->format('m'), $day );
    //     }

    // }

    // protected function parseRange($from, $to){
    //     $from = $this->parseDay($from);
    //     $to = $this->parseDay($to);
    //     if($from > $to){
    //         $from->setDate($now->format('Y'),$now->format('m') - 1, $day );
    //     }
    //     return [$from,$to];
    // }
}