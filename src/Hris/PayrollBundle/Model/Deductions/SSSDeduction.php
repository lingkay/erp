<?php

namespace Hris\PayrollBundle\Model\Deductions;

class SSSDeduction
{
	static function getDeductionAmount($employee, $em){
		//$this->employee = $employee;

		$query = "SELECT sss FROM HrisPayrollBundle:PaySSSRate sss WHERE (sss.min_amount <= :salary AND sss.max_amount >= :salary)";

        $sss = $em->createQuery($query)
            ->setParameter('salary', $employee->getMonthlyRate())
            ->getSingleResult();

        return $sss->getEmployeeContribution();
	}
}