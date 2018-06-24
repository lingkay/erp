<?php

namespace Hris\ReportBundle\Model;

use Doctrine\ORM\EntityManager;
use Gist\UserBundle\Entity\User;
use Hris\AdminBundle\Entity\Benefit;
use Hris\AdminBundle\Model\SettingsManager;
use Hris\WorkforceBundle\Entity\Employee;
use Hris\WorkforceBundle\Entity\Profile;
use Hris\WorkforceBundle\Entity\Appraisal;
use Hris\WorkforceBundle\Entity\Evaluator;
use Hris\WorkforceBundle\Entity\EmployeeChecklist;
use Hris\WorkforceBundle\Entity\EmployeeBenefits;
use Hris\WorkforceBundle\Entity\EmployeeLeaves;
use Hris\WorkforceBundle\Entity\SalaryHistory;
use Gist\NotificationBundle\Entity\Notification;
use Gist\NotificationBundle\Model\NotificationEvent;
use DateTime;

class AttendanceReportManager
{
    protected $em;
    protected $container;
    protected $user;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getData($employee_id, $area_id, $pos_loc_id, $date)
    {
        $list_opts = [];
        try {
            $query = $this->em->createQueryBuilder();

            //get attendances
            $query->from('HrisWorkforceBundle:Attendance', 'o')

                ->where('o.date LIKE :date')
                ->setParameter('date', '%'.$date->format('Y-m-d').'%');

                if ($employee_id != null && $employee_id != 'null' && $employee_id != 0) {
                    $query->andWhere('o.employee = :employee')
                        ->setParameter('employee', $employee_id);
                }

                if ($pos_loc_id != null && $pos_loc_id != 'null' && $pos_loc_id != 0) {
                    $query->andWhere('o.pos_location = :pos_location')
                        ->setParameter('pos_location', $pos_loc_id);
                }

            $posAttendances = $query->select('o')
                ->groupBy('o.employee')
                ->getQuery()
                ->getResult();    

            foreach ($posAttendances as $posAttendance) {
                 $gt = 0;
                $mainWorkIN_DATE = $posAttendance->getDate();
                $mainWorkOUT_DATE = '';

                //SCHEDULE
                $querySchedule = $this->em->createQueryBuilder();
                $querySchedule->from('HrisToolsBundle:ScheduleEntry', 'o')
                    ->leftJoin('o.schedule', 's')
                    ->where('o.employee = :employee')
                        ->setParameter('employee', $posAttendance->getEmployee()->getID());

                    $querySchedule->andWhere('s.date LIKE :date')
                        ->setParameter('date', '%'.$date->format('Y-m-d').'%');

                $schedule = $querySchedule->select('o')
                    ->getQuery()
                    ->getResult();

                //MAIN ARRAY
                $mainArray = [
                    'firstEntry' => [
                        'employee_name' => $posAttendance->getEmployee()->getDisplayName(),
                        'location' => $posAttendance->getPOSLocation()->getName(),
                        'date' => $posAttendance->getDate()->format('m-d-Y'),
                        'time_in' => $posAttendance->getDate()->format('h:i A'),
                    ],
                    'lastEntry' => [
                        'location' => '',
                        'time_out' => '',
                        'total_work' => '',
                        'grand_total' => '',
                    ],
                    'breaks' => [],
                    'transfers' => [],
                    'totals' => [
                        'break' => 0,
                        'transfer' => 0,
                        'grand_total' => 0
                    ]
                ];

                if ($schedule != null && $schedule != 'null' && $schedule != 0) {
                    $mainArray += [
                        'schedEntry' => [
                            'sched_location' => $schedule[0]->getPOSLocation()->getName(),
                            'sched_type' => $schedule[0]->getType(),
                        ]
                    ];
                }

                //BREAKS
                $queryBreaks = $this->em->createQueryBuilder();
                $queryBreaks->from('HrisWorkforceBundle:Attendance', 'o')
                    ->where('o.type = \'BREAK\'')
                    ->andWhere('o.date LIKE :date')
                        ->setParameter('date', '%'.$date->format('Y-m-d').'%');
                

                    $queryBreaks->andWhere('o.employee = :employee')
                        ->setParameter('employee', $posAttendance->getEmployee()->getID());
                

                if ($pos_loc_id != null && $pos_loc_id != 'null' && $pos_loc_id != 0) {
                    $queryBreaks->andWhere('o.pos_location = :pos_location')
                        ->setParameter('pos_location', $pos_loc_id);
                }

                $breaks = $queryBreaks->select('o')
                    ->getQuery()
                    ->getResult(); 

                $breaksArr = [];
                $breaksCtr = 0;
                $totalBreakHRS = 0;
                $date1 = '';
                $date2 = '';
                foreach ($breaks as $qb) {
                    
                    if ($qb->getStatus() == 'IN') {
                        $breaksArr[$breaksCtr]['break_in'] = $qb->getDate()->format('h:i A');
                        $date1 = $qb->getDate();
                    }

                    if ($qb->getStatus() == 'OUT') {
                        $breaksArr[$breaksCtr]['break_out'] = $qb->getDate()->format('h:i A');
                        $date2 = $qb->getDate();
                        $diff = $date2->diff($date1);
                        $hour= ($diff->h);
                        $minutes= round(($diff->i/60),2);
                        $breaksArr[$breaksCtr]['total_break'] = $hour+$minutes;
                        $totalBreakHRS += floatval($hour+$minutes);
                        $breaksCtr++;
                        $date1 = '';
                        $date2 = '';
                    }
                }

                $mainArray['totals']['break'] = $totalBreakHRS;
                $gt -= $totalBreakHRS;

                $mainArray['breaks'] = $breaksArr;    

                //TRANSFERS
                $queryTransfers = $this->em->createQueryBuilder();
                $queryTransfers->from('HrisWorkforceBundle:Attendance', 'o')
                    ->where('o.type = \'TRANSFER\'')
                    ->andWhere('o.date LIKE :date')
                        ->setParameter('date', '%'.$date->format('Y-m-d').'%');

                    $queryTransfers->andWhere('o.employee = :employee')
                        ->setParameter('employee', $posAttendance->getEmployee()->getID());

                if ($pos_loc_id != null && $pos_loc_id != 'null' && $pos_loc_id != 0) {
                    $queryTransfers->andWhere('o.pos_location = :pos_location')
                        ->setParameter('pos_location', $pos_loc_id);
                }

                $transfers = $queryTransfers->select('o')
                    ->getQuery()
                    ->getResult(); 

                $transferArr = [];
                $transferCtr = 0;
                $totalTransferHRS = 0;
                $date1 = '';
                $date2 = '';
                foreach ($transfers as $qb) {
                    if ($qb->getStatus() == 'IN') {
                        $transferArr[$transferCtr]['transfer_in'] = $qb->getDate()->format('h:i A');
                        $transferArr[$transferCtr]['location_in'] = $qb->getPOSLocation()->getName();
                        $date1 = $qb->getDate();
                    }

                    if ($qb->getStatus() == 'OUT') {
                        $transferArr[$transferCtr]['transfer_out'] = $qb->getDate()->format('h:i A');
                        $transferArr[$transferCtr]['location_out'] = $qb->getPOSLocation()->getName();
                        $date2 = $qb->getDate();
                        $diff = $date2->diff($date1);
                        $hour= ($diff->h);
                        $minutes= round(($diff->i/60),2);
                        $transferArr[$transferCtr]['total_transfer'] = $hour+$minutes;
                        $totalTransferHRS += floatval($hour+$minutes);
                        $transferCtr++;
                        $date1 = '';
                        $date2 = '';
                    }
                }

                $mainArray['totals']['transfer'] = $totalTransferHRS;
                $gt -= $totalTransferHRS;

                $mainArray['transfers'] = $transferArr;   

                //LAST ENTRY 
                $queryLastEntry = $this->em->createQueryBuilder();
                $queryLastEntry->from('HrisWorkforceBundle:Attendance', 'o')
                    ->where('o.type = \'WORK\'')
                    ->andWhere('o.status = \'OUT\'')
                    ->andWhere('o.date LIKE :date')
                        ->setParameter('date', '%'.$date->format('Y-m-d').'%');
                

                    $queryLastEntry->andWhere('o.employee = :employee')
                        ->setParameter('employee', $posAttendance->getEmployee()->getID());
                

                if ($pos_loc_id != null && $pos_loc_id != 'null' && $pos_loc_id != 0) {
                    $queryLastEntry->andWhere('o.pos_location = :pos_location')
                        ->setParameter('pos_location', $pos_loc_id);
                }

                $queryLastEntry->setMaxResults(1);

                $lastEntry = $queryLastEntry->select('o')
                    ->orderBy('o.date', 'DESC')
                    ->getQuery()
                    ->getResult(); 

                if (count($lastEntry) > 0) {
                    $mainArray['lastEntry']['location'] = $lastEntry[0]->getPOSLocation()->getName();
                    $mainArray['lastEntry']['time_out'] = $lastEntry[0]->getDate()->format('h:i A');
                    $mainWorkOUT_DATE = $lastEntry[0]->getDate();
                    $diff = $mainWorkOUT_DATE->diff($mainWorkIN_DATE);
                    $hour= ($diff->h);
                    $minutes= round(($diff->i/60),2);
                    $mainArray['lastEntry']['total_work'] = $hour+$minutes;
                    $gt += floatval($hour+$minutes);
                }   
                $mainArray['totals']['grand_total'] = $gt;

                $list_opts[] = $mainArray;
            }   

            return $list_opts;

        } catch (\Exception $e) {
            echo $e->getMessage();
        }    
    }
    
}
