<?php

namespace Hris\PayrollBundle\Model\Deductions;
use Hris\WorkforceBundle\Entity\Employee;

abstract class Deduction
{
	//abstract public function getDeductionAmount(Employee $employee);

	/**
	*	This is used to compute the monthly rate of an employee for Benefits purposes
	*/
	public static function getMonthlyRate($employee)
	{
		switch($employee->getPayPeriod()->getPaydays())
		{
			//Daily 
			case 312 : return $employee->getPayRate() * 24;
					break;

			//Weekly 
			case 52 : return $employee->getPayRate() * 4;
					break;

			//Semi Monthly
			case 24 : return $employee->getPayRate() * 2;
					break;

			//Monthly
			case 12 : 
			default: return $employee->getPayRate();
					break;
		}
	}

	public static function getDailyRate($employee)
	{
		switch($employee->getPayPeriod()->getPaydays())
		{
			//Weekly
			case 52 : return $employee->getPayRate()/6;
					break;

			//Semi Monthly
			case 24 : return $employee->getPayRate()/12;
					break;

			//Monthly
			case 12 : 
			default: return $employee->getPayRate()/24;
					break;

			//Daily
			case 312 : 
			default : return $employee->getPayRate();
					break;
		}
	}

}

