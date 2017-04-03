<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;

use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\EntityManager;

use Hris\AdminBundle\Entity\Holiday;

use Gist\CoreBundle\Template\Controller\TrackCreate;

use DateTime;
class HolidayController extends CrudController
{
    use TrackCreate;

    public function __construct()
    {
        $this->route_prefix = 'hris_admin_holiday';
        $this->title = 'Holiday';

        $this->list_title = 'Holiday';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new Holiday();
    }

    protected function update($o, $data,$is_new = false){

        $this->updateTrackCreate($o,$data, $is_new);
        $date = new DateTime($data['date']);
        $o->setName($data['name']);
        $o->setType($data['type']);
        $o->setDate($date);
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
            $grid->newColumn('Date', 'getDateDisplay', 'date'),
            $grid->newColumn('Name', 'getName', 'name'),
            $grid->newColumn('Type', 'getType', 'type'),
        );
    }

    protected function padFormParams(&$params, $object = NULL){

        $params['type_opts'] = array(
            'Regular Holiday' => 'Regular Holiday',
            "Special Non-Working" => 'Special Non-Working day'
            );
    }

    public function ajaxHolidayAction($month = null,$year = null){

        $em = $this->getDoctrine()->getManager();

        if($month < 10)
        {
            $month = '0' . $month;
        }

        $holiday = $year.'-'.$month.'-%';

        $query = "SELECT h FROM HrisAdminBundle:Holiday h WHERE h.date LIKE :holiday";

        $data = $em->createQuery($query)
            ->setParameter('holiday', $holiday)
            ->getResult();

        $arr = [];

        foreach ($data as $holiday) {
            $date = $holiday->getDate();
            $arr[] = array('date_formatted'=>$date->format('F d'),'holiday'=>$holiday->getName(), 'date'=>$date->format('m/d/Y'));
        }

        $resp = new Response(json_encode($arr));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function ajaxAllHolidayAction($month = null,$year = null){

        $em = $this->getDoctrine()->getManager();

        $data = $em->getRepository('HrisAdminBundle:Holiday')->findAll();
        foreach ($data as $holiday)
        {
            $arr[] = array('title'=>$holiday->getName(), 'start'=>$holiday->getDate()->format('Y-m-d'));
        }

        $resp = new Response(json_encode($arr));
        $resp->headers->set('Content-Type', 'application/json');
        return $resp;
    }
}
