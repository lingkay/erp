<?php

namespace Hris\PayrollBundle\Model\Earnings;

class ReimbursementEarning
{
	static function getEarningsAmount($payroll, $container){

		$cm = $container->get('hris_cashflow');
        $reimbursements = $cm->getReimbursementsByDate($payroll->getEmployee(), $payroll->getPayrollPeriod()->getStartDate(), $payroll->getPayrollPeriod()->getEndDate());
        $amount = 0 ;

        if($reimbursements != null){
            foreach ($reimbursements as $reimbursement) {
                $amount += $reimbursement->getCost();  
            }
        }

        return $amount;
	}
}