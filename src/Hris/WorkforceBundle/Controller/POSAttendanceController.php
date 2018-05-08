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

    public function receiveAttendanceAction($employee_id, $pos_loc_id, $status, $type, $date_time)
    {
        header("Access-Control-Allow-Origin: *");
        try {
            $em = $this->getDoctrine()->getManager();
            $dateFMTD = DateTime::createFromFormat('Y-m-d H:i:s', $date_time);
            $user = $em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>$employee_id));
            $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));

            $att = new Attendance();
            $att->setEmployee($user);
            $att->setDate($dateFMTD);
            $att->setStatus($status);
            $att->setType($type);
            $att->setPOSLocation($pos_location);
            $em->persist($att);
            $em->flush();

            if ($att) {
                return new JsonResponse(['success'=>true]);
            }

            return new JsonResponse(['success'=>false]);

        } catch (\Exception $e) {
            return  new JsonResponse(['success'=>false]);
        }
    }

    public function getLastEntryAction($employee_id, $date_time)
    {
        header("Access-Control-Allow-Origin: *");
        try {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQueryBuilder();
            $dateFMTD = DateTime::createFromFormat('Y-m-d', $date_time);
            $query->from('HrisWorkforceBundle:Attendance', 'o')
                ->where('o.date LIKE :date')
                ->andWhere('o.employee = :employee_id')
                ->orderBy('o.date', 'DESC')
                ->setParameter('date', '%'.$dateFMTD->format('Y-m-d').'%')
                ->setParameter('employee_id', $employee_id);

            $posAttendances = $query->select('o')
                ->getQuery()
                ->setMaxResults( 1 )
                ->getResult();
            $list_opts = [];
            foreach ($posAttendances as $posAttendance) {
                $list_opts[] = array(
                    'user_id' => $posAttendance->getEmployee()->getID(),
                    'user_name' => $posAttendance->getEmployee()->getDisplayName(),
                    'entry_id' => $posAttendance->getID(),
                    'datetime' => $posAttendance->getDateDisplay(),
                    'status' => $posAttendance->getStatus(),
                    'type' => $posAttendance->getType()
                );
            }

            return new JsonResponse($list_opts);
        } catch (\Exception $e) {
            return new JsonResponse(['success'=>false]);
        }
    }

    public function getAllByDateAction($employee_id, $date_time)
    {
        header("Access-Control-Allow-Origin: *");
        try {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQueryBuilder();

            $dateFMTD = DateTime::createFromFormat('Y-m-d', $date_time);
            $query->from('HrisWorkforceBundle:Attendance', 'o')
                ->where('o.date LIKE :date')
                ->andWhere('o.employee = :employee_id')
                ->setParameter('date', '%'.$dateFMTD->format('Y-m-d').'%')
                ->setParameter('employee_id', $employee_id);

            $posAttendances = $query->select('o')
                ->getQuery()
                ->getResult();

            $list_opts = [];
            foreach ($posAttendances as $posAttendance) {
                $list_opts[] = array(
                    'id' => $posAttendance->getID(),
                    'user_id' => $posAttendance->getEmployee()->getID(),
                    'user_name' => $posAttendance->getEmployee()->getDisplayName(),
                    'entry_id' => $posAttendance->getID(),
                    'datetime' => $posAttendance->getDateDisplay(),
                    'status' => $posAttendance->getStatus(),
                    'type' => $posAttendance->getType()
                );
            }

            return new JsonResponse($list_opts);
        } catch (\Exception $e) {
            return new JsonResponse(['success'=>$e->getMessage()]);
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