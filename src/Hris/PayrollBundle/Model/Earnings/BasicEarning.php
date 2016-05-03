<?php

namespace Hris\PayrollBundle\Model\Earnings;
use Hris\PayrollBundle\Entity\PayPeriod;
class BasicEarning
{
	static function getEarningsAmount($employee){

		 switch($employee->getPaySchedule()->getName()){
            case PayPeriod::TYPE_WEEKLY:
                return $employee->getPayRate() * 6;
                break;
            case PayPeriod::TYPE_SEMIMONTHLY:
                return $employee->getPayRate() / 2;
                break;
            case PayPeriod::TYPE_MONTHLY:
                return $employee->getPayRate();
                break;
        }
	}
}