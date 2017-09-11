<?php

namespace Hris\PayrollBundle\Model\Earnings;

class IncentiveEarning
{
	static function getEarningsAmount($payroll, $container){

		$cm = $container->get('hris_cashflow');
        $incentives = $cm->getIncentivesByDate($payroll->getEmployee(), $payroll->getPayrollPeriod()->getStartDate(), $payroll->getPayrollPeriod()->getEndDate());
        $amount = 0 ;

        // if($incentives != null){
        //     foreach ($reimbursements as $reimbursement) {
        //         $amount += $reimbursement->getCost();  
        //     }
        // }


        return $incentives;
	}
}