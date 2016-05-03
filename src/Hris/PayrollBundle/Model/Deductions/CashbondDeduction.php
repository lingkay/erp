<?php

namespace Hris\PayrollBundle\Model\Deductions;

class CashbondDeduction
{
	static function getDeductionAmount($employee, $em){

		$contribution = $employee->getCashbondRate();

        return $contribution;
	}
}