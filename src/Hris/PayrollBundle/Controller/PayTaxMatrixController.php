<?php

namespace Hris\PayrollBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

use Hris\PayrollBundle\Entity\PayTaxMatrix;

use Hris\PayrollBundle\Entity\PayTaxRate;
use Hris\PayrollBundle\Entity\PayTaxStatus;
use Hris\PayrollBundle\Entity\PayPeriod;

use DateTime;

class PayTaxMatrixController extends CrudController
{
	public function __construct()
	{
		$this->route_prefix = 'hris_payroll_tax';
		$this->title = 'Withholding Tax Table';

		$this->list_title = 'Withholding Tax Table';
		$this->list_type = 'dynamic';
	}

    protected function getObjectLabel($object) 
    {
        if ($object == null){
            return '';
        }
        return $object->getTaxStatus()->getCode();
    } 

    protected function newBaseClass() {
        return new PayTaxMatrix();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('period', 'period', 'getPayPeriod'),
            $grid->newJoin('status', 'status_id', 'getTaxStatus'),
            $grid->newJoin('rate', 'rate_id', 'getTaxRate'),

        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array( 
            $grid->newColumn('Exemption Status', 'getCode', 'code','status'),
            $grid->newColumn('Payroll Period', 'getName', 'name','period'),
            $grid->newColumn('Base Tax', 'getTax', 'tax','rate'),
            $grid->newColumn('Percent Of Excess Tax', 'getExcess', 'excess','rate'),
            $grid->newColumn('Base Amount', 'getBaseAmount', 'base_amount'),           
        );
    }

    public function update($o, $data, $is_new = false)
    {

        $payroll = $this->get('hris_payroll');

        $em = $this->getDoctrine()->getManager();
        
        $tax_rate = new PayTaxRate();
        $tax_status = new PayTaxStatus();

        $bracket = $data['min_amount'].'-'.$data['max_amount'];

        $pay_period = $em->getRepository('HrisPayrollBundle:PayPeriod')->find($data['payroll']);

        $tax_rate->setBracket($bracket);
        $tax_rate->setMinimum($data['min_amount']);
        $tax_rate->setMaximum($data['max_amount']); 
        $tax_rate->setTax($data['tax']);
        $tax_rate->setExcess($data['excess']);
        
        if($data['dependents'] == 0) {
            $tax_status->setCode($data['status']);
        }
        elseif($data['dependents'] <= 4) {
            $code = explode('/', $data['status']);
            $code = $code[0].$data['dependents'].'/'.$code[1].$data['dependents'];
            $tax_status->setCode($code);
        }
        else {
            $code = $data['status'].'4';
            $tax_status->setCode($code);
        }

        if($data['status'] == 'Z') {
            $tax_status->setPersonal(0);
            $tax_status->setAdditional(0);
            $tax_status->setTotal(0);
        }
        else {
            $exemption = $payroll->computeAdditionalExemption($data['dependents']);
            $total = $payroll->computeTotalExemption($exemption);
            
            $tax_status->setPersonal(PayTaxStatus::PERSONAL_EXEMPTION); 
            $tax_status->setAdditional($exemption);
            $tax_status->setTotal($total);
        }

        $em->persist($tax_rate);
        $em->persist($tax_status);

        $em->flush();

        $o->setTaxRate($tax_rate);
        $o->setTaxStatus($tax_status);
        $o->setPayPeriod($pay_period);
    }

    protected function padFormParams(&$params, $object = null)
    {
        $payroll = $this->get('hris_payroll');

        $params['sched_opts'] = $payroll->getPayPeriodOptions();
        $params['status_opts'] = array(
            'Z' => 'Zero Exemption',
            'ME/S' => 'Single/Married Employee',
            );
        
        if($object->getTaxStatus() != null) {
            $status = preg_replace('/[0-9]+/', '', $object->getTaxStatus()->getCode());
            $dependents = preg_replace('/[^\d]+/', '', $object->getTaxStatus()->getCode());
            $params['status'] = $status;
            
            if($dependents != null){
                $params['dependent'] = $dependents[0];    
            }
        
        }

        return $params;
    }
}