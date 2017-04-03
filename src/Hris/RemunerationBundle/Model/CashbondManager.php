<?php

namespace Hris\RemunerationBundle\Model;

use Doctrine\ORM\EntityManager;
use Hris\PayrollBundle\Entity\PayDeductionEntry;
use Hris\RemunerationBundle\Entity\Cashbond;
use Hris\RemunerationBundle\Entity\CashbondTransaction;

use Gist\UserBundle\Entity\User;

use DateTime;

class CashbondManager
{
    protected $em;
    protected $container;
    protected $configs;

	public function __construct(EntityManager $em, $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function newTransaction()
    {
    	return new CashbondTransaction();
    }

    public function getCashbondLoan($id)
    {
        return $this->em->getRepository('HrisRemunerationBundle:CashbondLoan')->find($id);
    }

    public function findCashbond($employee)
    {
    	$cashbond = $this->em->getRepository('HrisRemunerationBundle:Cashbond')
    		->findOneByEmployee($employee);

    	if($cashbond != null){
    		return $cashbond;
    	}else {
    		return new Cashbond($employee);
    	}
    }

    public function addContribution(PayDeductionEntry $entry)
    {
    	$transaction = $this->newTransaction();
    	$employee = $entry->getPayroll()->getEmployee();
    	$cashbond = $this->findCashbond($employee);

    	$transaction->setContribution($entry->getAmount());
    	$transaction->setPayrollPeriod($entry->getPayroll()->getPayrollPeriod());

    	$this->em->persist($transaction);
    	$this->em->flush();

    	$cashbond->addTransaction($transaction);

    	$this->em->persist($cashbond);
    	$this->em->flush();
    }

    public function getEmployeeWithCashbond()
    {
    	$all = $this->em->getRepository('HrisRemunerationBundle:Cashbond')->findAll();

    	$employees = [];
    	foreach ($all as $cashbond) {
    		$employees[] = $cashbond->getEmployee();
    	}

    	return $employees;
    }

    public function getEmployeeWithCashbondOpts()
    {
    	$employees = $this->getEmployeeWithCashbond();
    	$opts = ['0'=>'Select Employee'];
    	foreach ($employees as $employee){
            $opts[$employee->getID()] = $employee->getDisplayName();
    	}

        return $opts;

    }
}