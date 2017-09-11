<?php

namespace Hris\PayrollBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

use Hris\PayrollBundle\Entity\PaySchedule;


use DateTime;

class PayScheduleController extends CrudController
{
    use TrackCreate;

	public function __construct()
	{
		$this->route_prefix = 'hris_payroll_schedule';
		$this->title = 'Payroll Schedule Settings';

		$this->list_title = 'Payroll Schedule Settings';
		$this->list_type = 'dynamic';
	}

    protected function getObjectLabel($object)
    {
        if ($object == null){
            return '';
        }
        return $object->getName();
    } 

    protected function newBaseClass() {
        return new PaySchedule();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('p', 'period', 'getPeriod')
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Schedule Name', 'getName', 'name'),
            $grid->newColumn('Payroll Period', 'getName', 'name','p'),

        );
    }

    public function update($o, $data, $is_new = false)
    {

        $payroll = $this->get('hris_payroll');

        $em = $this->getDoctrine()->getManager();
        $o->setName($data['name']);
        $o->setPeriod($payroll->getPayType($data['period']));
        $o->setStartEnd($data['startend']);
        $this->updateTrackCreate($o, $data,$is_new);

    }

    protected function padFormParams(&$params, $object = null)
    {
        $payroll = $this->get('hris_payroll');

        $params['sched_opts'] = $payroll->getPayPeriodOptions();

        $params['days_opts'] = [ 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday','Saturday', 'Sunday'];
        $params['dates_opts'] = [ 'End of the month',1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31];
        

        return $params;
    }
}