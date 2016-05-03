<?php

namespace Hris\PayrollBundle\Model\Deductions;

class AdvanceDeduction
{
	static function getDeductionAmount($employee, $em){

		$salary = $employee->getMonthlyRate();
		if($salary >= 5000){
            $contribution = 100;
        }
        else{
            $contribution = $salary * (2/100);
        }

        return $contribution;
	}
}