<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Hris\AdminBundle\Entity\AppraisalSettings;

class AppraisalSettingsController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_admin_appraisal_settings';
        $this->title = 'Appraisal Settings';

        $this->list_title = 'Appraisal Presets';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new AppraisalSettings();
    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();
        $params['gender_opts'] = array(0 => 'Male', 1=> 'Female');

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();

        $o->setName($data['name']);
        $o->setObjCount($data['obj_count']);

        $divisor = $data['obj_count'];
        $percentage = 60 / $divisor;

        $o->setObjPercentage($percentage);

        // error_log(print_r($data,true));

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
            $grid->newColumn('Setting Name', 'getName', 'name'),
            $grid->newColumn('No. of Objectives', 'getObjCount', 'obj_count'),
        );
    }
}
