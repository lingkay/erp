<?php

namespace Hris\AdminBundle\Model;

use Doctrine\ORM\EntityManager;
use Hris\AdminBundle\Entity\Department;
use Hris\AdminBundle\Entity\Schedules;
use Hris\WorkforceBundle\Entity\Employee;
use Hris\AdminBundle\Entity\HRISConfigEntry;

class SettingsManager
{
    protected $em;

    public function __construct(EntityManager $em, $container = null)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function getDepartment($id)
    {
        return $this->em->getRepository('HrisAdminBundle:Department')->find($id);
    }

    public function getUser($id)
    {
        return $this->em->getRepository('GistUserBundle:User')->find($id);
    }

    public function getEmployee($id)
    {
        return $this->em->getRepository('HrisWorkforceBundle:Employee')->find($id);
    }

    public function getJobTitle($id)
    {
        return $this->em->getRepository('HrisAdminBundle:JobTitle')->find($id);
    }

    public function getJobLevel($id)
    {
        return $this->em->getRepository('HrisAdminBundle:JobLevel')->find($id);
    }

    public function getLocation($id)
    {
        return $this->em->getRepository('HrisAdminBundle:Location')->find($id);
    }

    public function getSchedule($id)
    {
        return $this->em->getRepository('HrisAdminBundle:Schedules')->find($id);
    }

    public function getChecklist($id)
    {
        return $this->em->getRepository('HrisAdminBundle:Checklist')->find($id);
    }

    public function getEmployeesByJobTitle($id)
    {
        $job = $this->getJobTitle($id);
        
        return $this->em->getRepository('HrisWorkforceBundle:Employee')
        ->findBy(
                array('job_title' => $job)
            );
    }

    public function getChecklists()
    {
         $opts = $this->em
            ->getRepository('HrisAdminBundle:Checklist')
            ->findBy(
                array(),
                array('id' => 'ASC')
            );
        return $opts;
    }

    public function getDepartmentOptions($filter = array())
    {
        $dept = $this->em
            ->getRepository('HrisAdminBundle:Department')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        $dept_opts = array();
        foreach ($dept as $wh)
            $dept_opts[$wh->getID()] = $wh->getName();

        return $dept_opts;
    }

    public function getJobTitleOptions($filter = array())
    {
        $opts = $this->em
            ->getRepository('HrisAdminBundle:JobTitle')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        $list_opts = array();
        foreach ($opts as $item)
            $list_opts[$item->getID()] = $item->getName();

        return $list_opts;
    }

    public function getVPOperationUsers($filter = array())
    {
        //change this to get vp operations department ID
        //$vp_ops_dept = 54;
        $vp_ops_dept = $this->em->getRepository("HrisAdminBundle:JobTitle")->findBy(array('name'=> 'VP Operations'));
        
        $query = 'select d from HrisWorkforceBundle:Employee d where d.job_title = :code';
        $opts = $this->em ->createQuery($query)
                    ->setParameter('code', $vp_ops_dept)   
                    ->getResult();

        $list_opts = array();
        foreach ($opts as $item)
            $list_opts[$item->getID()] = $item->getFirstName().' '.$item->getLastName();

        return $list_opts;
    }

    public function getPresidentUsers($filter = array())
    {
        //change this to get vp operations department ID
        //$vp_ops_dept = 54;
        $vp_ops_dept = $this->em->getRepository("HrisAdminBundle:JobTitle")->findBy(array('name'=> 'President/CEO'));
        
        $query = 'select d from HrisWorkforceBundle:Employee d where d.job_title = :code';
        $opts = $this->em ->createQuery($query)
                    ->setParameter('code', $vp_ops_dept)   
                    ->getResult();

        $list_opts = array();
        foreach ($opts as $item)
            $list_opts[$item->getID()] = $item->getFirstName().' '.$item->getLastName();

        return $list_opts;
    }

    public function getJobLevelOptions($filter = array())
    {
        $opts = $this->em
            ->getRepository('HrisAdminBundle:JobLevel')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        foreach ($opts as $item)
            $list_opts[$item->getID()] = $item->getName();

        return $list_opts;
    }

    public function getEmploymentStatusOptions($filter = array())
    {
        $list_opts = array(Employee::EMP_CONTRACTUAL => Employee::EMP_CONTRACTUAL,
            Employee::EMP_PROBATIONARY => Employee::EMP_PROBATIONARY,
            Employee::EMP_REGULAR => Employee::EMP_REGULAR,
            Employee::EMP_RESIGNED => Employee::EMP_RESIGNED
        );

        return $list_opts;
    }

    public function getLocationOptions($filter = array())
    {
        $opts = $this->em
            ->getRepository('HrisAdminBundle:Location')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        $list_opts = [];
        foreach ($opts as $item)
            $list_opts[$item->getID()] = $item->getName();

        return $list_opts;
    }

    public function getChecklistOptions($filter = array())
    {
        $opts = $this->em
            ->getRepository('HrisAdminBundle:Checklist')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        $list_opts = array();
        foreach ($opts as $item)
            $list_opts[$item->getID()] = $item->getName();

        return $list_opts;
    }

    public function getSchedulesOptions($filter = array())
    {
        $opts = $this->em
                     ->getRepository('HrisAdminBundle:Schedules')
                     ->findBy(
                         $filter,
                         array('name' => 'ASC')
                    );

        $sched_opts = array();
        foreach ($opts as $sched)
            $sched_opts[$sched->getID()] = $sched->getName().' ('.$sched->getDisplaySchedule().')';
        
        return $sched_opts;
    }

    public function getDepartmentHeadcount()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(e) as employees, e as emp')
            ->from('HrisWorkforceBundle:Employee', 'e')
            ->where('e.enabled = ?1')
            ->andWhere('e.employment_status != \'Resigned\'')
            ->setParameter('1', true)
            ->groupBy('e.department')
        ;

        $dept = $qb->getQuery()->getResult();
        $list = array();
        foreach($dept as $item)
        {
            $list[] = array( $item['emp']->getDepartment()->getName(),  intval($item['employees']));
        }
        return $list;
    }

    public function getRegularCount()
    {
        return count($this->em->getRepository("HrisWorkforceBundle:Employee")->findBy(array('enabled'=> true, 'employment_status'=> Employee::EMP_REGULAR)));
    }

    public function getProbationCount()
    {
        return count($this->em->getRepository("HrisWorkforceBundle:Employee")->findBy(array('enabled'=> true, 'employment_status'=> Employee::EMP_PROBATIONARY)));
    }

    public function getContractualCount()
    {
        return count($this->em->getRepository("HrisWorkforceBundle:Employee")->findBy(array('enabled'=> true, 'employment_status'=> Employee::EMP_CONTRACTUAL)));
    }

    public function getResignedCount()
    {
        return count($this->em->getRepository("HrisWorkforceBundle:Employee")->findBy(array('employment_status'=> Employee::EMP_RESIGNED)));
    }

    public function set($id, $value)
    {
        $em = $this->em;
        $entry = $em->getRepository('HrisAdminBundle:HRISConfigEntry')->find($id);

        if ($entry == null)
        {
            $entry = new HRISConfigEntry($id, $value);

            $em->persist($entry);

            return $this;
        }

        // if it's there already
        $entry->setValue($value);

        return $this;
    }

    public function get($id)
    {
        $em = $this->em;
        $entry = $em->getRepository('HrisAdminBundle:HRISConfigEntry')->find($id);

        if ($entry == null)
            return null;

        return $entry->getValue();
    }

    public function getValueTypeOptions($filter = array())
    {
        $em = $this->em;
        $whs = $em
            ->getRepository('HrisAdminBundle:ValueTypes')
            ->findBy(
                $filter,
                array('id' => 'ASC')
            );

        $valueTypeOptions = array();
        foreach ($whs as $wh)
            $valueTypeOptions[$wh->getID()] = $wh->getName();

        return $valueTypeOptions;
    }

    public function getFinesValueTypeOptions($filter = array())
    {
        $em = $this->em;
        $whs = $em
            ->getRepository('HrisAdminBundle:FineValueTypes')
            ->findBy(
                $filter,
                array('id' => 'ASC')
            );

        $finesValueTypeOptions = array();
        foreach ($whs as $wh)
            $finesValueTypeOptions[$wh->getID()] = $wh->getName();

        return $finesValueTypeOptions;
    }

    public function getIncentivePeriodOptions($filter = array())
    {
        $em = $this->em;
        $whs = $em
            ->getRepository('HrisAdminBundle:IncentivePeriod')
            ->findBy(
                $filter,
                array('id' => 'ASC')
            );

        $incentivePeriods = array();
        foreach ($whs as $wh)
            $incentivePeriods[$wh->getID()] = $wh->getName();

        return $incentivePeriods;
    }

    public function getBonusOptions($filter = [])
    {
        $em = $this->em;
        $entries = $em
            ->getRepository('HrisAdminBundle:Bonus')
            ->findBy(
                $filter,
                array('id' => 'ASC')
            );

        $options = array();
        foreach ($entries as $entry)
            $options[$entry->getID()] = $entry->getName();

        return $options;
    }

      public function getFineOptions($filter = [])
    {
        $em = $this->em;
        $entries = $em
            ->getRepository('HrisAdminBundle:Fines')
            ->findBy(
                $filter,
                array('id' => 'ASC')
            );

        $options = array();
        foreach ($entries as $entry)
            $options[$entry->getID()] = $entry->getName();

        return $options;
    }

    public function getDepositOptions($filter = [])
    {
        $em = $this->em;
        $entries = $em
            ->getRepository('HrisAdminBundle:Deposit')
            ->findBy(
                $filter,
                array('id' => 'ASC')
            );
        $options = array();
        foreach ($entries as $entry)
            $options[$entry->getID()] = $entry->getName();

        return $options;
    }

    public function findDepositType($id)
    {
        return $this->em->getRepository('HrisAdminBundle:Deposit')->find($id);
    }

    public function findBonus($id)
    {
        return $this->em->getRepository('HrisAdminBundle:Bonus')->find($id);
    }

    
    public function findFine($id)
    {
        return $this->em->getRepository('HrisAdminBundle:Fines')->find($id);
    }


    public function getPOSLocations()
    {
        $em = $this->em;
        $entries = $em
            ->getRepository('Gist\LocationBundle\Entit:POSLocations')
            ->findBy(
                $filter,
                array('id' => 'ASC')
            );

        return $entries;
    
    }

    



}
