<?php

namespace Hris\PayrollBundle\Model\Deductions;

class LoanDeduction
{
	static function getDeductionAmount($payroll, $container){

	$cm = $container->get('hris_cashflow');
        $payments = $cm->getLoanPaymentsByDate($payroll->getEmployee(), $payroll->getPayrollPeriod()->getStartDate(), $payroll->getPayrollPeriod()->getEndDate());
        // if($incentives != null){
        //     foreach ($reimbursements as $reimbursement) {
        //         $amount += $reimbursement->getCost();  
        //     }
        // }

        return $payments;
	}
}