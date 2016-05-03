<?php

namespace Hris\PayrollBundle\Model\Deductions;

class PhilhealthDeduction
{
	static function getDeductionAmount($employee, $em){
		//$this->employee = $employee;

		$query = "SELECT ph FROM HrisPayrollBundle:PayPhilHealthRate ph WHERE (ph.min_amount <= :salary AND ph.max_amount >= :salary)";

        $sss = $em->createQuery($query)
            ->setParameter('salary', $employee->getMonthlyRate())
            ->getSingleResult();

        return $sss->getEmployee();
	}
}