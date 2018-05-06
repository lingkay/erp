<?php

namespace Hris\WorkforceBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Gist\ValidationException;
use Hris\WorkforceBundle\Entity\Attendance;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use DateTime;
use SplFileObject;
use LimitIterator;

class POSAttendanceController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_workforce_pos_attendance';
        $this->title = 'POS Attendance';

        $this->list_title = 'POS Attendance';
        $this->list_type = 'dynamic';
    }

    public function indexAction()
    {

    }

    public function receiveAttendanceAction($employee_id, $pos_loc_id, $status, $date_time)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $dateFMTD = DateTime::createFromFormat('m-d-Y H:i:s', $date_time);
            $user = $em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>$employee_id));
            $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));

            $att = new Attendance();
            $att->setEmployee($user);
            $att->setDate($dateFMTD);
            $att->setStatus($status);
            $att->setPOSLocation($pos_location);
            $em->persist($att);
            $em->flush();

            if ($att) {
                return JsonResponse(['success'=>true]);
            }

            return JsonResponse(['success'=>false]);

        } catch (\Exception $e) {
            return JsonResponse(['success'=>false]);
        }
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
        return new Attendance();
    }
}