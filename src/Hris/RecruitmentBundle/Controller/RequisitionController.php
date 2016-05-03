<?php

namespace Hris\RecruitmentBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Hris\RecruitmentBundle\Entity\ManpowerRequest;
use Catalyst\CoreBundle\Template\Controller\TrackCreate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Catalyst\NotificationBundle\Model\NotificationEvent;
use Catalyst\NotificationBundle\Entity\Notification;
use DateTime;

class RequisitionController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_requisition';
        $this->title = 'Manpower Requisition';
        $this->list_title = 'Manpower Requisition';
        $this->list_type = 'dynamic';
        $this->repo = "HrisRecruitmentBundle:ManpowerRequest";
        $this->submit_redirect = false;
    }

    protected function getObjectLabel($obj) {
        if ($obj == null){
            return '';
        }
        return $obj->getPosition()->getName();
    }

    protected function newBaseClass() {
        return new ManpowerRequest();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');
        return array(
            $grid->newColumn('Date Filed', 'getDateFiled','date_filed'),
            $grid->newColumn('Position Title', 'getName', 'name', 'p'),
            $grid->newColumn('Department', 'getName','name', 'd'),
            $grid->newColumn('Requested By', 'getName', 'name','u'),
            $grid->newColumn('Job Vacancy', 'getVacancy','vacancy'),
            $grid->newColumn('Status', 'getStatus', 'status'),
        );
    }

    protected function getGridJoins()
    {
        $grid = $this->get('catalyst_grid');
        return array(
            $grid->newJoin('d', 'department', 'getDepartment'),
            $grid->newJoin('u', 'user_create', 'getUserCreate'),
            $grid->newJoin('p', 'position', 'getPosition')
        );
    }

    protected function populateArray($data, $opts = null)
    {
        // $opts = $this->getEmpStat();
        $foo = array();

        if(!empty($opts)) {
            foreach ($opts as $id => $emp) {
                foreach ($data as $entry) {
                    if($entry == $id)
                        $foo[$id] = $emp;
                }
            }
        }
        else {
            foreach ($data as $entry) {
                array_push($foo, $entry);
            }
        }

        return $foo;
    }

    protected function update($o, $data, $is_new = false)
    {
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();
        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $config = $this->get('catalyst_configuration');


        $this->updateTrackCreate($o, $data, $is_new);

        switch($data['action_btn'])
        {
            case "Save":
                $o->setDateFiled(new DateTime($data['date_issue']));
                $this->updateData($o, $data, $settings, $wf, $is_new = false);
            break;

            case "Approved":
                $o->setDateApproved(new DateTime());
                $o->setStatus(ManpowerRequest::STATUS_APPROVED);
                $o->setApprovedBy($this->getUser()->getEmployee());
            break;

            case "Reviewed":
                $o->setDateReceived(new DateTime());
                $o->setStatus(ManpowerRequest::STATUS_REVIEW);
                $o->setRecommendedBy($this->getUser()->getEmployee());
                $this->updateData($o, $data, $settings, $wf, $is_new = false);
            break;

            case "Reject":
                $o->setStatus(ManpowerRequest::STATUS_DENIED);
            break;
        }
    }

    protected function updateData($o, $data, $settings, $wf, $is_new = false)
    {
        // position details
        if (isset($data['position'])) {
            $o->setPosition($settings->getJobTitle($data['position']))
                ->setDepartment($settings->getDepartment($data['department']))
                ->setVacancy($data['vacancies']);
        }
        // set general details
        foreach ($data as $id => $val) {
            unset($foo);
            if(is_array($data[$id])) {
                switch($id){
                    case 'gender':
                        $foo = $this->populateArray($val, array('MALE' => 'Male', 'FEMALE' => 'Female'));
                        $o->setGender($foo);
                        break;
                    case 'experience':
                        $foo = $this->populateArray($val, $this->getExpOpts());
                        $o->setExperience($foo);
                        break;
                    case 'education':
                        $foo = $this->populateArray($val, $this->getEducOpts());
                        $o->setEducation($foo);
                        break;
                    case 'required_course':
                        $foo = $this->populateArray($val);
                        $o->setRequiredCourses($foo);
                        break;
                    case 'skills':
                        $foo = $this->populateArray($val);
                        $o->setSkills($foo);
                        break;
                    case 'terms':
                        $foo = $this->populateArray($val, $this->getTermsOpts());
                        $o->setTermsOfEmployment($foo);
                        break;
                    case 'purpose':
                        $foo = $this->populateArray($val, $this->getPurposeOpts());
                        $o->setPurpose($foo);
                        break;
                    case 'personnel_type':
                        $foo = $this->populateArray($val, $this->getPersonOpts());
                        $o->setPersonnelType($foo);
                        break;
                }
            }
        }

        if(isset($data['flag_service'])) {
            foreach ($data['flag_service'] as $id => $service) {
                unset($bar);
                $bar = array();
                if($service == 1) {
                    $o->setInternalSourceCode($data['i_source_code']);
                    foreach ($data['i_candidates'] as $internal) {
                        array_push($bar, $internal);
                    }
                    $o->setInternalCandidates($bar);
                }

                if($service == 2) {
                    $o->setExternalSourceCode($data['e_source_code']);
                    foreach ($data['e_candidates'] as $external) {
                        array_push($bar, $external);
                    }
                    $o->setExternalCandidates($bar);
                }
            }
        }
        
        // set other requirements
        $o->setAgeFrom($data['age_from']);
        $o->setAgeTo($data['age_to']);
        $o->setNotes($data['notes']);
    }

    protected function notify($receipient,$link,$message,$source)
    {
        $event = new NotificationEvent();
        $event->notify(array(
            'source'=> $source,
            'link'=> $link,
            'message'=> $message,
            'type'=> Notification::TYPE_UPDATE,
            'receipient' => $receipient));

        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch('notification.event', $event);
    }

    public function hookPostSave($obj, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $config = $this->get('catalyst_configuration');
        $hr = $settings->getDepartment($config->get('hris_hr_department'));
        
        $pos_name = $obj->getPosition()->getName();
        $status = $obj->getStatus();
        $dept = $obj->getDepartment();
        $req_id = $obj->getID();

        if ($is_new) {
            $source = 'New manpower request submitted.';
            $message = 'Manpower request for '.$pos_name.' has been submitted and ready to be reviewed.';
            $link = $this->generateUrl('hris_requisition_edit_form',array('id'=>$req_id));

            $dept_head = $dept->getDeptHead();
            $hr_recruitment = $settings->getEmployeesByJobTitle($config->get('hris_hr_recruit'));
            $hr_admin = $wf->getEmployees(array('department'=>$hr->getID()));

            // notify department head
            $this->notify($dept_head, $link, $message, $source);

            // notify all employee with HR Recruitment position
            foreach ($hr_recruitment as $recruit) {
                $this->notify($recruit, $link, $message, $source);
            }

            // notify all employee with HR Admin Role
            foreach ($hr_admin as $admin) {
                if ($admin->getUser()->hasGroup('hr_admin')) {
                    $this->notify($admin, $link, $message, $source);
                }
            }
        }
        else
        {
            if($status == "Reviewed") {
                $source = 'Manpower Requisition needs your approval.';
                $message = 'Manpower request for '.$pos_name.' has been reviewed and needs your approval.';
                $link = $this->generateUrl('hris_requisition_edit_form',array('id'=>$req_id));
                
                $vp_ops = $settings->getEmployeesByJobTitle($config->get('hris_vp_operations'));

                foreach ($vp_ops as $vp) {
                    $this->notify($vp, $link, $message, $source);
                }
            }
            elseif($status == "Approved") {
                $source = 'Manpower Requisition has been approved';
                $message = 'Manpower request for '.$pos_name.' has been approved and ready for application.';
                $link = $this->generateUrl('hris_requisition_edit_form',array('id'=>$req_id));

                $hr_recruitment = $settings->getEmployeesByJobTitle($config->get('hris_hr_recruit'));
                $hr_admin = $wf->getEmployees(array('department'=>$hr->getID()));
                $dept_head = $dept->getDeptHead();

                // notify Department Head
                $this->notify($dept_head, $link, $message, $source);

                // notify creator if not dept_head
                if ($dept_head->getID() !== $obj->getUserCreate()->getEmployee()->getID()) {
                    $this->notify($obj->getUserCreate()->getEmployee(), $link, $message, $source);
                }

                // notify all employee with HR Recruitment position
                foreach ($hr_recruitment as $recruit) {
                    $this->notify($recruit, $link, $message, $source);
                }

                // notify all employee with HR Admin Role
                foreach ($hr_admin as $admin) {
                    if ($admin->getUser()->hasGroup('hr_admin')) {
                        $this->notify($admin, $link, $message, $source);
                    }
                }
            }
            elseif($status == "Denied") {
                 $source = 'Manpower Requisition has been denied.';
                $message = 'Manpower request for '.$pos_name.' has been reviewed and denied.';
                $link = $this->generateUrl('hris_requisition_edit_form',array('id'=>$req_id));

                $hr_recruitment = $settings->getEmployeesByJobTitle($config->get('hris_hr_recruit'));
                $hr_admin = $wf->getEmployees(array('department'=>$hr->getID()));
                $dept_head = $dept->getDeptHead();

                // notify Department Head
                $this->notify($dept_head, $link, $message, $source);

                // notify creator if not dept_head
                if ($dept_head->getID() !== $obj->getUserCreate()->getEmployee()->getID()) {
                    $this->notify($obj->getUserCreate()->getEmployee(), $link, $message, $source);
                }

                // notify all employee with HR Recruitment position
                foreach ($hr_recruitment as $recruit) {
                    $this->notify($recruit, $link, $message, $source);
                }

                // notify all employee with HR Admin Role
                foreach ($hr_admin as $admin) {
                    if ($admin->getUser()->hasGroup('hr_admin')) {
                        $this->notify($admin, $link, $message, $source);
                    }
                }
            }
        } 
    }

    protected function padFormParams(&$params, $object = NULL){
        $settings = $this->get('hris_settings');

        $params['department_opts'] = $settings->getDepartmentOptions();
        $params['status_opts'] = $settings->getEmploymentStatusOptions();
        $params['account_opts'] = array(1 => 'Enabled', 0=>'Disabled');
        $params['gender_opts'] = array('MALE' => 'Male', 'FEMALE' => 'Female');
        $params['sched_opts'] = $settings->getSchedulesOptions();
        $params['experience_opts'] = $this->getExpOpts();
        $params['education_opts'] = $this->getEducOpts();
        $params['terms_opts'] = $this->getTermsOpts();
        $params['purpose_opts'] = $this->getPurposeOpts();
        $params['personnel_opts'] = $this->getPersonOpts();
        $params['jobtitle_opts'] = $settings->getJobTitleOptions();
        $params['current_user'] = $this->getUser()->getName();

        if ($object != NULL) {
            // $params['dept_head'] = $object->getPosition()->getDepartment()->getDeptHead();
            // $params['readonly'] = $this->checkReadonly($object);
            // $params['button'] = $this->checkButtonsAccess($object);
        }
        
        return $params;
    }

    protected function checkReadonly($object)
    {
        // check if reviewed, approved, denied, if true, form should be readonly
        if ($object->getStatus() == 'Reviewed' 
            or $object->getStatus() == 'Approved' 
            or $object->getStatus() == 'Denied') {
            return true;
        }
        
        // if not reviewed/approved/denied, check if user is Admin, return false for readonly
        elseif ($this->getUser()->getName() == 'Administrator') {
            return false;
        }

        // check involving object data
        elseif ($object->getId() != NULL) {
            if ($object->getPosition()->getDepartment()->getDeptHead() == NULL) {
                return true;
            }
            // check of current user is dept head for corresponding requisition
            elseif ($this->getUser()->getEmployee()->getId()
                == $object->getPosition()->getDepartment()->getDeptHead()->getId()) {
                return false;
            }
            // check if current user is the requestor, else readonly is true
            elseif ($object->getUserCreate()->getName() !== $this->getUser()->getName()) {
                return true;
            }
            else
                return false;
        }
        else
            return false;
    }

    protected function checkButtonsAccess($object)
    {
        $button = [];
        // Fix dept head then include in filter

        // if user is admin, all buttons are available
        if ($this->getUser()->getName() == 'Administrator') {
            if ($object->getId() == NULL) {
                $button['submit']['has_access'] = true;
                $button['review']['has_access'] = false;
                $button['approve']['has_access'] = false;
                $button['reject']['has_access'] = false;
            }
            elseif ($object->getUserCreate()->getName() !== 'Administrator'
                and $object->getStatus() == 'Draft') {
                $button['submit']['has_access'] = false;
                $button['review']['has_access'] = true;
                $button['approve']['has_access'] = false;
                $button['reject']['has_access'] = true;
            }
            elseif ($object->getStatus() == 'Draft') {
                $button['submit']['has_access'] = true;
                $button['review']['has_access'] = true;
                $button['approve']['has_access'] = false;
                $button['reject']['has_access'] = true;
            }
            elseif ($object->getStatus() == 'Reviewed') {
                $button['submit']['has_access'] = false;
                $button['review']['has_access'] = false;
                $button['approve']['has_access'] = true;
                $button['reject']['has_access'] = true;
            }
            elseif ($object->getStatus() == 'Denied' or $object->getStatus() == 'Approved') {
                $button['submit']['has_access'] = false;
                $button['review']['has_access'] = false;
                $button['approve']['has_access'] = false;
                $button['reject']['has_access'] = false;
            }
        }

        // if user is not admin, apply filter
        else {
            if ($object->getId() != NULL) {
                if ($object->getStatus() !== 'Draft'
                    or $this->getUser()->getName() !== $object->getUserCreate()->getName()) {
                    $button['submit']['has_access'] = false;
                    $button['reject']['has_access'] = false;
                }
                else {
                    $button['submit']['has_access'] = true;
                    $button['reject']['has_access'] = true;
                }

                if ($object->getPosition()->getDepartment()->getDeptHead() == NULL ) {
                    $button['review']['has_access'] = false;
                    $button['reject']['has_access'] = false;
                }
                elseif ($object->getPosition()->getDepartment()->getDeptHead()->getId()
                    !== $this->getUser()->getEmployee()->getId()) {
                    $button['review']['has_access'] = false;
                    $button['reject']['has_access'] = false;
                }
                elseif ($object->getStatus() !== 'Draft'
                    or $object->getStatus() == 'Reviewed') {
                    $button['review']['has_access'] = false;
                    $button['reject']['has_access'] = false;
                }
                else {
                    $button['review']['has_access'] = true;
                    $button['reject']['has_access'] = true;
                }

                if ($object->getStatus() !== 'Reviewed'
                    or $this->getUser()->getEmployee()->getJobTitle()->getName() !== 'VP Operations') {
                    $button['approve']['has_access'] = false;
                    $button['reject']['has_access'] = false;
                }
                else {
                    $button['approve']['has_access'] = true;
                    $button['reject']['has_access'] = true;
                }
            }
            else {
                $button['submit']['has_access'] = true;
                $button['review']['has_access'] = false;
                $button['approve']['has_access'] = false;
                $button['reject']['has_access'] = false;
            }
        }

        return $button;
    }

    protected function getExpOpts()
    {
        $exp_opts = array(
            "fresh_grad" => "Fresh Graduate",
            "one_yr" => "1",
            "two_yr"=>"2",
            "more_yr"=>"3 or More"
        );
        return $exp_opts;
    }

    protected function getEducOpts()
    {
        $educ_opts = array();
        $educ_opts = [
            "Doctorate Degree", 
            "Professional License (Passed Board/Bar/Professional License Exam)",
            "Post Graduate Diploma / Master's Degree",
            "Bachelor's / College Degree",
            "Vocational Diploma / Short Course Certificate",
            "High School Diploma"
        ];
        return $educ_opts;
    }

    protected function getTermsOpts()
    {
        $terms_opts = array();
        $terms_opts = [
            "Probationary for Permanent Job",
            "Temporary for Normal Operations",
            "Project Hire (Contractual)",
            "On-the-Job Trainee",
            "Agency Hire"
        ];
        return $terms_opts;
    }

    protected function getPurposeOpts()
    {
        $purpose_opts = array();
        $purpose_opts = [
            "Newly Created Position",
            "Transfer Replacement",
            "Separation Replacement",
            "Resignation Replacement",
            "Promotion Replacement",
            "Others"
        ];
        return $purpose_opts;
    }

    protected function getPersonOpts()
    {
        $person_opts = array();
        $person_opts = [
            "Managerial",
            "Supervisory",
            "Monthly Paid",
            "Daily Paid","
            Temporary Seasonal Worker",
            "Professional / Technical"
        ];
        return $person_opts;
    }

    public function printReqAction($id)
    {
        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $twig = "HrisRecruitmentBundle:Requisition:print.html.twig";

        //params here
        $data = $this->getRequest()->query->all();
        $em = $this->getDoctrine()->getManager();
        $conf = $this->get('catalyst_configuration');
        if ($conf->get('hris_com_info_company_name') != null) 
        {
            $params['company_name'] = strtoupper($conf->get('hris_com_info_company_name'));
        }

        if ($conf->get('hris_com_info_website') != null) 
        {
            $params['company_website'] = $conf->get('hris_com_info_website');
        }

        if ($conf->get('hris_com_info_company_address') != null) 
        {
            $params['company_address'] = $em->getRepository('CatalystContactBundle:Address')->find($conf->get('hris_com_info_company_address'));
        }

        $params['request'] = $em->getRepository('HrisRecruitmentBundle:ManpowerRequest')->find($id);

        $conf = $this->get('catalyst_configuration');
        $media = $this->get('catalyst_media');
        if ($conf->get('hris_com_logo') != '') 
        {
            $path = $media->getUpload($conf->get('hris_com_logo'));

            $str = $path->getURL();
            $str = parse_url($str, PHP_URL_PATH);
            $str = ltrim($str, '/');

            $params['logo'] = $str;
        }
        else
        {
            $params['logo'] = '';
        }


        $pdf = $this->get('catalyst_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }

    public function callbackGrid($id)
    {
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository('HrisRecruitmentBundle:ManpowerRequest')->find($id);
        $status = false;
        if($obj->getStatus() == ManpowerRequest::STATUS_DRAFT)
        {
            $status = true;
        }
        $params = array(
            'id' => $id,
            'status' => $status,
            'route_edit' => $this->getRouteGen()->getEdit(),
            'route_delete' => $this->getRouteGen()->getDelete(),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'HrisRecruitmentBundle:Requisition:action.html.twig',
            $params
        );
    }
}
