<?php

namespace Hris\WorkforceBundle\Model;

use Doctrine\ORM\EntityManager;
use Hris\WorkforceBundle\Entity\Employee;
use Hris\AdminBundle\Model\SettingsManager;
use Hris\WorkforceBundle\Entity\Reimbursement;
use Hris\WorkforceBundle\Entity\Advance;
use Hris\RemunerationBundle\Entity\Incentive;
use Hris\RemunerationBundle\Entity\Loan;
use Hris\RemunerationBundle\Entity\LoanPayment;

use DateTime;

class CashflowManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function generateReimbursementCode(Reimbursement $object)
    {
        return "REI-".str_pad($object->getID(), 7, '0', STR_PAD_LEFT);;
    }

    public function generateCashAdvanceCode(Advance $object)
    {
        return "ADV-".str_pad($object->getID(), 7, '0', STR_PAD_LEFT);;
    }

    public function generateIncentiveCode(Incentive $object)
    {
        return "INC-".str_pad($object->getID(), 7, '0', STR_PAD_LEFT);;
    }

    public function generateLoanCode(Loan $object)
    {
        return "LN-".str_pad($object->getID(), 7, '0', STR_PAD_LEFT);;
    }

    public function generateLoanPaymentCode(LoanPayment $object)
    {
        return "PAY-".str_pad($object->getID(), 7, '0', STR_PAD_LEFT);;
    }

    public function getLoan($id)
    {
        return $this->em->getRepository('HrisRemunerationBundle:Loan')->find($id);
    }

    public function getReimbursementsByDate($employee, $date_from, $date_to)
    {
        $query = "SELECT a FROM HrisWorkforceBundle:Reimbursement a WHERE (a.date_approved BETWEEN :dfrom AND :dto) AND a.employee = :id AND a.status = :status ";

        $qry = $this->em->createQuery($query)
            ->setParameter('id', $employee)
            ->setParameter('status', 'Approved')
            ->setParameter('dfrom', $date_from)
            ->setParameter('dto', $date_to);

        $reimbursements = $qry->getResult();
        return $reimbursements;
    }

    public function getIncentivesByDate($employee, $date_from, $date_to)
    {
        $query = "SELECT a FROM HrisRemunerationBundle:Incentive a WHERE (a.date_approved BETWEEN :dfrom AND :dto) AND a.employee = :id AND a.status = :status ";

        $qry = $this->em->createQuery($query)
            ->setParameter('id', $employee)
            ->setParameter('status', 'Approved')
            ->setParameter('dfrom', $date_from)
            ->setParameter('dto', $date_to);

        $incentives = $qry->getResult();
        return $incentives;
    }

    public function getLoanPaymentsByDate($employee, $date_from, $date_to)
    {
        $query = "SELECT a FROM HrisRemunerationBundle:LoanPayment a JOIN a.loan b
                    WHERE (a.date_paid BETWEEN :dfrom AND :dto) AND b.employee = :id ";

        $qry = $this->em->createQuery($query)
            ->setParameter('id', $employee)
            ->setParameter('dfrom', $date_from)
            ->setParameter('dto', $date_to);

        $loanPayments = $qry->getResult();
        return $loanPayments;
    }
}