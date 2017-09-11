<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\AdminBundle\Entity\Events;
use DateTime;

class EventsController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_admin_events';
        $this->title = 'Events';

        $this->list_title = 'Events';
        $this->list_type = 'static';
    }

    protected function newBaseClass() 
    {
        return new Events();
    }
    
    protected function update($o, $data, $is_new = false)
    {   
        $evm = $this->get('hris_events');
        $evm->checkForEventsTomorrow();
        $em = $this->getDoctrine()->getManager();

        if (isset($data['allday'])) 
        {
            $start = new DateTime($data['event_date'].'12:00 AM');
            $end = new DateTime($data['event_date'].'11:59 PM');
            
            $o->setDateFrom($start);
            $o->setDateTo($end);
            $o->setName($data['event_name']);
            $o->setHolidayType($data['event_holiday_type']);
            $this->updateTrackCreate($o,$data,$is_new);
        }
        else
        {     
            $start = new DateTime($data['date_from'].$data['start']);
            $end = new DateTime($data['date_to'].$data['end']);
            
            $o->setDateFrom($start);
            $o->setDateTo($end);
            $o->setName($data['event_name']);
            $o->setHolidayType($data['holiday_type']);
            $this->updateTrackCreate($o,$data,$is_new);
        }
    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();
        $params['holiday_opts'] = array('Company Event' => 'Company Event', 'Regular Holiday' => 'Regular Holiday', 'Special Non-Working' => 'Special Non-Working', 'Others' => 'Others');

        return $params;
    }

    public function editFormAction($id)
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->find($id);

        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');

        $this->padFormParams($params, $obj);

        return $this->render('HrisAdminBundle:Events:edit.html.twig', $params);
    }


    protected function getObjectLabel($obj) 
    {
        if ($obj == null){
            return '';
        }
        return $obj->getName();
    }  

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Name','getName','name'),
            $grid->newColumn('Date From','getDateFrom','name'),
            $grid->newColumn('Date To','getDateTo','name'),
            $grid->newColumn('Type','getHolidayType','name'),
        );
    }

    public function getAllEventsAction($month = null,$year = null){

        $em = $this->getDoctrine()->getManager();

        $arr[] = array('title'=>'','start'=>'1800-12-12T00:00:00','end'=>'1800-12-12T00:00:00');

        $data = $em->getRepository('HrisAdminBundle:Events')->findAll();
        foreach ($data as $event) 
        {
            if (($event->getDateFrom2()->format('Y-m-d') == $event->getDateTo2()->format('Y-m-d')) && ($event->getDateFrom2()->format('H:i:s') == "00:00:00" && $event->getDateTo2()->format('H:i:s') == "23:59:00")) 
            {
                $arr[] = array('allDay'=>true,'title'=>$event->getName(), 'start'=>$event->getDateFrom2()->format('Y-m-d').'T'.$event->getDateFrom2()->format('H:i:s'), 'end'=>$event->getDateTo2()->format('Y-m-d').'T'.$event->getDateTo2()->format('H:i:s'));
            }
            else
            {
                $arr[] = array('title'=>$event->getName(), 'start'=>$event->getDateFrom2()->format('Y-m-d').'T'.$event->getDateFrom2()->format('H:i:s'), 'end'=>$event->getDateTo2()->format('Y-m-d').'T'.$event->getDateTo2()->format('H:i:s'));
            }
        }

        $resp = new Response(json_encode($arr));
        $resp->headers->set('Content-Type', 'application/json');
        return $resp;
    }


}
