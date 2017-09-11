<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\AdminBundle\Entity\Schedules;

use DateTime;

class SchedulesController extends CrudController
{
    
    use TrackCreate;

    public function __construct()
    {
        $this->route_prefix = 'hris_admin_schedules';
        $this->title = 'Employee Time Schedules';

        $this->list_title = 'Employee Time Schedules';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new Schedules();
    }

    public function update ($o, $data, $is_new = false)
    {

        // $start = new DateTime($data['start']);
        // $end = new DateTime($data['end']);
        // $o->setStart($start);
        // $o->setEnd($end);
        // $o->setDayStart($data['day_start']);
        // $o->setDayEnd($data['day_end']);
        // $o->setGracePeriod($data['grace_minutes']);
        // $o->setHalfday($data['halfday_minutes']);
        $o->setName($data['name']);
        $this->updateTrackCreate($o, $data, $is_new);
        // print_r($data);
        // die();
        
        $o->setDayStart($data['day_start']);
        $o->setDayEnd($data['day_end']);
        
        $o->setType($data['schedule_type']);

        if ($data['schedule_type'] == 'flexi') 
        {
            $o->setRequiredHours($data['required_hours']);
            $o->setGracePeriod(0);
            $o->setHalfday(0);
            $o->setStart(new DateTime());
            $o->setEnd(new DateTime());
        }
        else
        {
            $o->setGracePeriod($data['grace_minutes']);
            $o->setHalfday($data['halfday_minutes']);
            if (isset($data['semi_required_hours'])) 
            {
                $o->setRequiredHours($data['semi_required_hours']);
            }
            $start = new DateTime($data['start']);
            $end = new DateTime($data['end']);
            $o->setStart($start);
            $o->setEnd($end);
        }
    }

    protected function padFormParams(&$params, $object = null)
    {
        $params['days_opts'] = [ 'Monday'=>'Monday', 'Tuesday'=>'Tuesday', 
        'Wednesday'=>'Wednesday', 'Thursday'=>'Thursday', 'Friday'=>'Friday', 'Saturday'=>'Saturday', 'Sunday'=>'Sunday'];
        $params['schedule_types'] = array('flexi'=>'Flexible Time', 'semi-flexi'=>'Semi-Flexible Time', 'fixed'=>'Fixed');
    }

    protected function getObjectLabel($obj)
    {
        if ($obj == null)
        {
            return '';
        }
        return $obj->getName();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Employee Group','getName','name'),
            $grid->newColumn('Type', 'getTypeLabel', 'type'),
            $grid->newColumn('Time Schedule','getDisplaySchedule','name'),
            $grid->newColumn('Grace Period','getGracePeriodText','name'),
        );
    }
}
?>