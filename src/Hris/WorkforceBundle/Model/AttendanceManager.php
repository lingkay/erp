<?php

namespace Hris\WorkforceBundle\Model;

use Doctrine\ORM\EntityManager;
use Hris\WorkforceBundle\Entity\Employee;
use Hris\WorkforceBundle\Entity\Attendance;
use Gist\UserBundle\Entity\User;
use Hris\AdminBundle\Entity\Holiday;
use DateTime;

class AttendanceManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEmployee($id)
    {
        return $this->em->getRepository('HrisWorkforceBundle:Employee')->find($id);
    }

    public function isHoliday($date)
    {
        $holiday = $this->em->getRepository('HrisAdminBundle:Holiday')->findByDate($date);

        if($holiday != null){
            return true;
        }
        return false;
    }

    protected function isWorkingDay(Employee $employee, DateTime $date)
    {
        //Change this to be more flexible. currently just checks for sundays. should check an employee's schedule
        //Checks for sundays
        // if($date->format('N') == 7){
        //     return false;
        // }
        // return true;

        $schedule = $employee->getSchedule();
        $start_day = date('N', strtotime($schedule->getDayStart()));
        $end_day = date('N', strtotime($schedule->getDayEnd()));


        if ($date->format('N') >= $start_day && $date->format('N') <= $end_day) {
            return true;
        } else {
            return false;
        }


    }

    protected function isOnLeave(Employee $employee, DateTime $date)
    {

        $leaves = $this->em->getRepository('HrisWorkforceBundle:Leave')->findBy(array('employee' => $employee->getID()));
            foreach ($leaves as $leave) {

                $l_start = new DateTime($leave->getDateStart()->format('m/d/Y')." 00:00:00");
                $l_end = new DateTime($leave->getDateEnd()->format('m/d/Y')." 23:59:59");
                $curr = new DateTime($date->format('m/d/Y')." 00:00:00");


                if (($curr >= $l_start) && ($curr <= $l_end)) {
                    return true;
                    //echo 'LEAVE'.$leave->getID();
                }
                else
                {
                    // echo "EMP NAME: ".$employee->getFirstName().'<br />';
                    // echo 'LEAVE'.$leave->getID().'<br />';
                    // echo 'LEAVE START: '.$l_start->format('m/d/Y h:i:s').'<br />';
                    // echo 'LEAVE END: '.$l_end->format('m/d/Y h:i:s').'<br />';
                    // echo 'CURR DATE: '.$date->format('m/d/Y h:i:s').'<br />'; 
                    return false;
                }
            }     
    }


    protected function checkDay(Employee $employee, DateTime $date)
    {
        if($this->isHoliday($date)){
            $holiday = $this->em->getRepository('HrisAdminBundle:Holiday')->findOneByDate($date);
            if($holiday->getType() == Holiday::TYPE_REGULAR)
            {
                $type = Attendance::STATUS_HOLIDAY;
            }
            else
            {
                $type = Attendance::STATUS_ABSENTNONWORKING;
            }
        }else if(!$this->isWorkingDay($employee, $date)){
            $type = Attendance::STATUS_NONWORKING;
        }else if($this->isOnLeave($employee, $date)){
            $type = Attendance::STATUS_PAIDLEAVE;
        }else {
            $type = Attendance::STATUS_ABSENT;
        }

        return $this->createAttendance($employee, $date, $type);
    }

    public function checkHolidayType(Employee $employee, DateTime $date)
    {
        if($this->isHoliday($date))
        {
            $holiday = $this->em->getRepository('HrisAdminBundle:Holiday')->findOneByDate($date);
            if($holiday != null)
            {

                if(!$this->isWorkingDay($employee, $date))
                {
                    return Attendance::STATUS_DOUBLEHOLIDAYRESTDAY;
                }
                return Attendance::STATUS_DOUBLEHOLIDAY;
            }
            if($holiday->getType() == Holiday::TYPE_REGULAR)
            {
                if(!$this->isWorkingDay($employee, $date))
                {
                    return Attendance::STATUS_HOLIDAYRESTDAY;
                }
                return Attendance::STATUS_HOLIDAY;
            }
            else if($holiday->getType() == Holiday::TYPE_SPECIAL)
            {
                if(!$this->isWorkingDay($employee, $date))
                {
                    return Attendance::STATUS_NONWORKINGRESTDAY;
                }
                return Attendance::STATUS_HOLIDAYNONWORKING;
            }
        }
        else
        {
            if(!$this->isWorkingDay($employee,$date))
            {
                return Attendance::STATUS_NONWORKING;
            }
            return Attendance::STATUS_PRESENT;
        }
    }

    protected function createAttendance(Employee $employee, DateTime $date, $type)
    {
        $attendance = new Attendance();
        $user = $this->em->getRepository('GistUserBundle:User')->findOneBy(array('name' => 'Administrator'));
        $attendance->setEmployee($employee)
                    ->setDate($date)
                    ->setUserCreate($user)
                    ->setStatus($type);

        $this->em->persist($attendance);
        $this->em->flush();
        return $attendance;
    }

    public function generateAttendanceEntry()
    {
        $date = new DateTime();
        $employees = $this->em->getRepository('HrisWorkforceBundle:Employee')->findByEnabled(true);

        foreach($employees as $employee)
        {
            $attendance = $this->em->getRepository('HrisWorkforceBundle:Attendance')
                ->findOneBy(
                    array('employee'=>$employee,
                        'date'=>$date)
                );

            if($attendance == null)
            {
                $this->checkDay($employee, $date);
            }
        }
    }

    public function getTotalAttendance($id = null, $attendance = null) {

        $query = 'SELECT a FROM HrisWorkforceBundle:Attendance a WHERE a.employee = :id AND a.date LIKE :attendance' ;

        $data = $this->em->createQuery($query)
            ->setParameter('attendance', $attendance)
            ->setParameter('id', $id)
            ->getResult();

        return $data;
    }

    public function getTotal($attendance)
    {

        $total_late = 0;
        $total_undertime = 0;
        $total_absent = 0;

        foreach ($attendance as $total)
        {
            if($total->getUnderTime() != 0)
            {
                $total_undertime++;
            }
            if($total->getLate() != 0)
            {
                $total_late++;
            }
            if($total->getStatus() == Attendance::STATUS_ABSENT)
            {
                $total_absent++;
            }
        }

        $total = array(
            'total_undertime' => $total_undertime ,
            'total_late' => $total_late,
            'total_absent' => $total_absent
            );

        return $total;
    }

    public function getDatesTardy($attendance)
    {
        $dates = array();
        foreach ($attendance as $att)
        {
            if($att->getLate() != 0)
            {
                //array_push($dates, $att->getDate()->format('F d, Y').' - '.$att->getLate().' minute(s) late');
                $dates[] = array("date" => $att->getDate()->format('F d, Y'), "in" => $att->getTimeIn(), "out" => $att->getTimeOut(), "late" => $att->getLateDisplay());
            }
        }
        return $dates;
    }

    public function getLate($start_sched,$time_in)
    {
        return ($time_in->getTimestamp() - strtotime($start_sched)) / 60;
    }

    public function getUndertime($end_sched,$time_out)
    {
        return ($time_out->getTimestamp() - strtotime($end_sched)) / 60;
    }

    //If an Employee has no entry on a specific date then create an absent entry
    public function fillDate(DateTime $date, $employee){
        return $this->checkDay($employee, $date);
    }
}