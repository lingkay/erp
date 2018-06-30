<?php

namespace Hris\ToolsBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\UserBundle\Utility\ManagerGroupName;
use Hris\ToolsBundle\Entity\Schedule;
use Hris\ToolsBundle\Entity\ScheduleEntry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Gist\TemplateBundle\Model\BaseController as Controller;
use Gist\ValidationException;
use Hris\WorkforceBundle\Entity\Attendance;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use DateTime;
use SplFileObject;
use LimitIterator;

class WeeklyScheduleController extends Controller
{
    public function __construct()
    {
        $this->route_prefix = 'hris_tools_weekly_schedule';
        $this->title = 'Weekly Schedule';
        $this->list_title = 'Weekly Schedule';
        $this->list_type = 'static';
        $this->repo = "HrisWorkforceBundle:Attendance";
    }

    public function indexAction($date = null)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $this->checkAccess($this->route_prefix . '.view');
            $params = $this->getViewParams('List', 'hris_tools_weekly_schedule_index');
            $this->padFormParams($params);
            $settings = $this->get('hris_settings');

            if ($date == null) {
                $date = new DateTime();
                $date = $date->format('m-d-Y');
            } 
            
            $week_dates = $this->getWeekDates($date);
            $dateFMTD = DateTime::createFromFormat('m-d-Y', $date);

            //check if logged-in user is an area manager -- if not disable page
            $user = $this->getUser();
            $params['is_manager'] = false;
            if ($user->getGroup()->getName() == ManagerGroupName::MANAGER_GROUP_NAME || $user->getUsername() == 'admin') {
                $params['is_manager'] = true;
            } else {
                $this->addFlash('error', 'You need to be an Area Manager to view and manage schedules!');
                return $this->redirect('/');//redirect with warning
            }

            //get locations where user's area == pos loc area
            $params['locations'] = $em->getRepository('GistLocationBundle:POSLocations')->findBy(array('area' => $user->getArea()->getID()));

            $pos_locations = array();
            foreach ($params['locations'] as $loc) {
                $pos_locations[$loc->getID()] = array(
                    'name' => $loc->getName(),
                    'week_dates' => @$week_dates,
                );
            }

            //find schedule entry
            if (isset($week_dates)) {                
                $params['week_dates'] = $week_dates;
                foreach ($week_dates as $day_id => $val) {
                    $new_date = DateTime::createFromFormat('m-d-Y', $val['date']);
                    $schedule = $em->getRepository('HrisToolsBundle:Schedule')->findOneBy(array('area' => $user->getArea()->getID(), 'date' => $new_date));

                    if ($schedule) {
                        if (count($schedule->getEntries()) > 0) {
                            foreach ($schedule->getEntries() as $ee) {
                                if ($ee->getType() == 'Work') {
                                    $pos_locations[$ee->getPOSLocation()->getID()]['week_dates'][$day_id]['entries'][] = array(
                                        'employee_id' => $ee->getEmployee()->getID(),
                                        'employee_name' => $ee->getEmployee()->getDisplayName()
                                    );
                                }
                            }
                        }
                    }
                }
            } 

            $params['week_schedule'] = $pos_locations;

            $params['date_to_url'] = $dateFMTD->format("m-d-Y");
            $params['filterDate'] = "Week ".$dateFMTD->format("W").", ".str_replace("-", "/", $week_dates[0]['date'])." - ".str_replace("-", "/", $week_dates[6]['date']);
            $params['list_title'] = $this->list_title;
            $params['prefix'] = $this->route_prefix;
            $twig_file = 'HrisToolsBundle:Weekly:index.html.twig';
            return $this->render($twig_file, $params);
        } catch (\Exception $e) {
            return $this->redirect('/');
        }
    }

    private function getWeekDates($date)
    {
        $new_date = str_replace('-', '/', $date);
        $ts = strtotime($new_date);
        $dow = date('w', $ts);
        $ts = $ts - $dow*86400;
        $week_dates = array();
        for ($i = 0; $i < 7; $i++, $ts += 86400){
            $week_dates[$i] = array(
                'date' => date('m-d-Y', $ts),
                'text' => date('l', $ts),
            );
        }

        return $week_dates;
    }

    protected function getObjectLabel($obj)
    {
        if ($obj == null)
        {
            return '';
        }
        return $obj->getID();
    }

    protected function newBaseClass()
    {
        return new AttendanceReport();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('emp','employee','getEmployee'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Date','getDateDisplay','date'),
        );
    }

    protected function padFormParams(&$params, $object = NULL){
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');

        return $params;
    }
}
