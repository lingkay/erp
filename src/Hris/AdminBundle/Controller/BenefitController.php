<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\AdminBundle\Entity\Benefit;
use Hris\WorkforceBundle\Entity\Employee;
use Symfony\Component\HttpFoundation\JsonResponse;

class BenefitController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_admin_benefit';
        $this->title = 'Benefit';

        $this->list_title = 'Benefit';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new Benefit();
    }

    protected function getEmpStat()
    {
        $emp_opts = array(
            1 => Employee::EMP_PROBATIONARY,
            2 => Employee::EMP_CONTRACTUAL,
            3 => Employee::EMP_REGULAR,
        );
        return $emp_opts;
    }

    protected function getBenType()
    {
        $char_opts = array(
            1 => Benefit::TYPE_DAYS,
            2 => Benefit::TYPE_MONEY,
        );
        return $char_opts;
    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');
        $params['emp_opts'] = $this->getEmpStat();
        $params['types'] = $this->getBenType();
        $params['checked'] = $o->getEmpStatus();
        $params['gender_opts'] = array(0 => 'Male', 1=> 'Female');
        $params['dept_opts'] = $settings->getDepartmentOptions();
        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {

        $o->setName($data['name']);
        $o->setNotes($data['notes']);
        $o->setDepartment($data['department']);
        unset($foo);
        $foo = array();

        $emp_opts = $this->getEmpStat();
        foreach ($emp_opts as $id => $emp) {
            foreach ($data['emp_opts'] as $entry) {
                if($entry == $id)
                    $foo[$id] = $emp;
            }
        }
        $o->setEmpStatus($foo);

        $gender = array();
        $gender_opts = array(0 => 'Male', 1 => 'Female');
        foreach ($gender_opts as $id => $gen) {
            foreach ($data['gender'] as $entry) {
                if($entry == $id)
                    $gender[$id] = $gen;
            }
        }
        $o->setGender($gender);

        $char_opts = $this->getBenType();
        foreach ($char_opts as $id => $char) {
            if($data['characteristics'] == $id)
                $o->setType($char);
        }

        $this->updateTrackCreate($o, $data, $is_new);
    }

    protected function getObjectLabel($obj) 
    {
        if ($obj == null){
            return '';
        }
        return $obj->getName();
    }  

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Benefit', 'getName', 'name'),
            $grid->newColumn('Description', 'getNotes', 'notes'),
            $grid->newColumn('Benefit Type', 'getType', 'type'),
        );
    }
}
