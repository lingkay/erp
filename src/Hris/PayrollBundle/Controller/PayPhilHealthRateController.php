<?php

namespace Hris\PayrollBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

use Hris\PayrollBundle\Entity\PayPhilHealthRate;

use DateTime;

class PayPhilHealthRateController extends CrudController
{
	public function __construct()
	{
		$this->route_prefix = 'hris_payroll_philhealth';
		$this->title = 'PhilHealth Contribution Table';

		$this->list_title = 'PhilHealth Contribution Table';
		$this->list_type = 'dynamic';
	}

    protected function getObjectLabel($object) 
    {
        if ($object == null){
            return '';
        }
        return $object->getBracket();
    } 

    protected function newBaseClass() {
        return new PayPhilHealthRate();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');
        return array( 
            $grid->newColumn('Minimum Amount', 'getMinimum', 'min_amount'),
            $grid->newColumn('Maximum Amount', 'getMaximum', 'max_amount'),
            $grid->newColumn('Employee Contribution', 'getEmployee', 'employee_contribution'),
            $grid->newColumn('Employer Contribution', 'getEmployer', 'employer_contribution'),
            $grid->newColumn('Total Contribution', 'getTotal', 'total_contribution'),          
        );
    }

    public function update($o, $data, $is_new = false)
    {
        $bracket = $data['min_amount'].'-'.$data['max_amount'];
        $o->setBracket($bracket);
        $o->setMinimum($data['min_amount']);
        $o->setMaximum($data['max_amount']);
        $o->setEmployee($data['ee']);
        $o->setEmployer($data['er']);
        $o->setTotal($data['total']);
    }
}