<?php

namespace Hris\RecruitmentBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Doctrine\ORM\EntityManager;

use Hris\RecruitmentBundle\Entity\Application;
use Hris\RecruitmentBundle\Entity\ApplicationProfile;
use Hris\RecruitmentBundle\Entity\ApplicationExperience;
use Hris\RecruitmentBundle\Entity\ApplicationReference;
use Hris\RecruitmentBundle\Entity\ApplicationInformation;
use Hris\RecruitmentBundle\Entity\ApplicationEducation;
use Hris\RecruitmentBundle\Entity\ApplicationSkills;
use Hris\RecruitmentBundle\Entity\ApplicationExam;
use Hris\RecruitmentBundle\Entity\ApplicationInterview;
use Hris\RecruitmentBundle\Entity\ApplicationChecklist;

use Catalyst\NotificationBundle\Model\NotificationEvent;
use Catalyst\NotificationBundle\Entity\Notification;
use Catalyst\CoreBundle\Template\Controller\TrackCreate;
use Catalyst\ContactBundle\Entity\Address;
use Catalyst\ContactBundle\Entity\Phone;

use Hris\WorkforceBundle\Entity\Employee;

use Catalyst\ValidationException;


use DateTime;

class ApplicationController extends CrudController
{
    use TrackCreate;

	public function __construct()
	{
		$this->route_prefix = 'hris_applications';
		$this->title = 'Applicant';

        $this->list_title = 'Applications';
		$this->list_type = 'dynamic';
        $this->submit_redirect = false;
	}

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List');


        $twig_file = 'HrisRecruitmentBundle:Application:list.dynamic.html.twig';


        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        return $this->render($twig_file, $params);
    }

    public function editProgressFormAction($id)
    {
        $this->checkAccess('hris_applications_edit_progress' . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->find($id);

        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess('hris_applications_edit_progress' . '.edit');

        $this->padFormParams($params, $obj);

        return $this->render('HrisRecruitmentBundle:Application:progress.html.twig', $params);
    }

    public function editProgressSubmitAction($id)
    {
        $this->checkAccess($this->route_prefix . '.edit');

        $this->hookPreAction();
            $em = $this->getDoctrine()->getManager();
            $data = $this->getRequest()->request->all();

            $object = $em->getRepository($this->repo)->find($id);

            // validate
            $this->validate($data, 'edit');

            // update db
            $this->update($object, $data);
            $em->flush();
            $this->hookPostSave($object);
            // log
            $odata = $object->toData();
            $this->logUpdate($odata);

            $this->addFlash('success', $this->title . ' ' . $this->getObjectLabel($object) . ' edited successfully.');

            return $this->redirect($this->generateUrl('hris_applications_edit_progress_form', array('id' => $id)).$this->url_append);
    }

    protected function update($o, $data, $is_new = false){

        $this->updateTrackCreate($o,$data,$is_new);
        $em = $this->getDoctrine()->getManager();

        switch ($data['app_form']) {

            case 'exam':
                    $this->updateExam($o,$data,$is_new);
            break;

            case 'interview':
                    $this->updateInterview($o,$data,$is_new);
            break;

            case 'offer':
                    $this->updateOffer($o,$data,$is_new);
            break;

            case 'background':
                    $this->updateBackground($o,$data,$is_new);
            break;

            case 'checklist':
                    $this->updateChecklist($o,$data,$is_new);
            break;

            case 'signing':
                    $this->updateSigning($o,$data,$is_new);
            break;

            case 'personal_info':
                    $this->updateProfile($o,$data,$is_new);
            break;

            case 'educ_background':
                    $this->updateEducation($o,$data,$is_new);
            break;

            case 'work_exp':
                    $this->updateExperience($o,$data,$is_new);
            break;

            case 'tech_skill':
                    $this->updateSkills($o,$data,$is_new);
            break;

            case 'char_ref':
                    $this->updateReference($o,$data,$is_new);
            break;

            case 'other_info':
                    $this->updateInformation($o,$data,$is_new);
            break;

            case 'upload':
                    $this->uploadPicture($o,$data,$is_new);
                break;
        }     
    }
    protected function uploadPicture($o,$data,$is_new)
    {
        $media = $this->get('catalyst_media');
        if($data['picture_id']!=0 && $data['picture_id'] != ""){
            $o->setUpload($media->getUpload($data['picture_id']));
        }

    }
    protected function updateExam($o,$data,$is_new)
    {
        $this->url_append = "#tab_exam";
        if(isset($data['applicant_appeared']) && $data['applicant_appeared'] == 0) {
            $exam = new ApplicationExam();
            $o->addExam($exam);
            $o->setAppeared(true);
            $o->setStatus(Application::STATUS_BLACKLISTEXAM);
        }

            if($o->getExam() == null)
            {
                $exam = new ApplicationExam();

            }
            else
            {
                $exam = $o->getExam();
            }
            $o->addExam($exam);
            $exam->setDate(new DateTime($data['exam_date']));
            $exam->setTime(new DateTime($data['exam_time']));
            if (isset($data['exam_id'])) 
            {
                $exam->setUserCreate($this->getUser());
                $result = array();
                foreach ($data['exam_id'] as $index => $value) {

                $result[$index] = array(
                    'type' => $value,
                    'score' => $data['score'][$index],
                    'result' => $data['status'][$index]
                    );
                }

                $exam->setResult($result);
                $exam->setStatus($data['result']);
            }
                //error_log($exam->getResult());
                if(isset($data['result']) && $data['result'] == 'pass') {
                    $this->url_append = "#tab_interview";
                    $o->setStatus(Application::STATUS_INTERVIEW);
                }
                else if(isset($data['result']) && $data['result'] == 'fail') {
                    $o->setStatus(Application::STATUS_FAILEDEXAM);
                }
    }

    protected function updateInterview($o,$data,$is_new)
    {
        $em = $this->getDoctrine()->getManager();
        $this->url_append = "#tab_interview";
  
            if($o->getInterview() == null)
            {
                $interview = new ApplicationInterview();
            }
            else 
            {
                $interview = $o->getInterview();
            }
            $o->addInterview($interview);
            $interview->setDate(new DateTime($data['interview_date']));
            $interview->setTime(new DateTime($data['interview_time']));
            if(isset($data['applicant_appeared']) && $data['applicant_appeared'] == 0) {
            $o->setAppeared(true);
            $o->setStatus(Application::STATUS_BLACKLISTINTERVIEW);

            $interview->setUserCreate($this->getUser());
            $interview->setDate(new DateTime($data['interview_date']));
            $interview->setTime(new DateTime($data['interview_time']));
            $interviewer = array();
            }
            if (isset($data['interview_id'])) 
            {
                $interview->setUserCreate($this->getUser());
                foreach ($data['interview_id'] as $index => $value) {
                    $employee = $em->getRepository('HrisWorkforceBundle:Employee')->find($data['interview_id'][$index]);
                    $interviewer[$index] = array(
                        'id' => $data['interview_id'][$index],
                        'interviewer' => $employee->getDisplayName(),
                        'interviewed_date' => $data['interviewed_date'][$index],
                        'comment' => $data['interview_remarks'][$index],
                        'recommendation' => $data[$value.'_recommendation']
                    );
                }  
                $interview->setResult($interviewer);    
            }
            $o->addInterview($interview);

            if(isset($data['result']) && $data['result'] == 'pass') {
                $this->url_append = "#tab_background";
                $o->setStatus(Application::STATUS_CHECK);
            }
            else if(isset($data['result']) && $data['result'] == 'fail') {
                $o->setStatus(Application::STATUS_FAILEDINTERVIEW);
            }

            //notify interviewer/s
    }

    protected function updateOffer($o,$data,$is_new)
    {
        $this->url_append = "#tab_offer";
        if(isset($data['applicant_appeared']) && $data['applicant_appeared'] == 0) {
            $o->setAppeared(true);
            $o->setStatus(Application::STATUS_BLACKLISTOFFER);
        }
        else {

                $jo = array(
                    'date' => new DateTime($data['offer_date']),
                    'time' => new DateTime($data['offer_time']),
                    'position' => $data['job_position'],
                    'level' => $data['job_level'],
                    'salary' => $data['salary'],
                    'allowance' => $data['allowance'],
                    'location' => $data['location'],
                    'schedule' => $data['schedule'],
                    'employment_status' => $data['employment_status'],
                    'pay_schedule' => $data['pay_sched'],
                    'pay_type' => $data['pay_type'],
                    'remark' => $data['remark']
                    );
                $o->setJobOffer($jo);

                if(isset($data['result']) && $data['result'] == 'pass') {
                    $this->url_append = "#tab_checklist";
                    $o->setStatus(Application::STATUS_CHECKLIST);
                }
                else if(isset($data['result']) && $data['result'] == 'fail') {
                    $o->setStatus(Application::STATUS_FAILEDOFFER);
                }
        }
    }

    protected function updateBackground($o,$data,$is_new)
    {
        $this->url_append = "#tab_background";
        if(isset($data['applicant_appeared']) && $data['applicant_appeared'] == 0) {
            $o->setAppeared(true);
            $o->setStatus(Application::STATUS_BLACKLIST);
        }
        else {
                $background = array(
                    'date' => new DateTime($data['background_date']),
                    'status' => $data['emp_status'],
                    'position' => $data['position'],
                    'reputation' => $data['reputation'],
                    'performance' => $data['performance'],
                    'dependability' => $data['dependability'],
                    'attendance' => $data['attendance'],
                    'responsibility' => $data['responsibility'],
                    'potential' => $data['potential'],
                    'get_along' => $data['get_along'],
                    'supervision' => $data['supervision'],
                    'points' => $data['points'],
                    'leaving' => $data['leaving'],
                    'comments' => $data['comments'],
                    'hire_back' => $data['hire_back'],
                    'name_answered' => $data['name_answered'],
                    'position_answered' => $data['position_answered'],
                    'date_answered' => new DateTime($data['date_answered']),
                    );

                $o->setBackgroundCheck($background); 

                if(isset($data['result']) && $data['result'] == 'pass') {
                    $this->url_append = "#tab_offer";
                    $o->setStatus(Application::STATUS_OFFER);
                }
                else if(isset($data['result']) && $data['result'] == 'fail') {
                    $o->setStatus(Application::STATUS_FAILEDCHECK);
                }
            }
    }

    // protected function updateChecklist($o,$data,$is_new)
    // {
    //     if(isset($data['applicant_appeared']) && $data['applicant_appeared'] == 0) {
    //         $o->setAppeared(false);
    //         $o->setStatus(Application::STATUS_BLACKLIST);
    //     }
    //     else {
    //         if($o->getChecklist() == null) {
    //             $checklist = array(
    //                 'date' => new DateTime($data['checklist_date']),
    //                 'status' => ''
    //                 );
    //             $o->setChecklist($checklist);
    //         }
    //         else {
    //             $checklist = array(
    //                 'date' => new DateTime($data['checklist_date']),
    //                 'remark' => $data['remark'],
    //                 'status' => $data['result']
    //                 );
    //             $o->setChecklist($checklist);

    //             if($o->getChecklist()['status'] == 'pass') {
    //                 $o->setStatus(Application::STATUS_SIGNING);
    //             } 
    //         }
    //     }
    // }

    protected function updateChecklist($o, $data, $is_new){
        $this->url_append = "#tab_checklist";;
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');

        //Clear checklist
        foreach ($o->getChecklist() as $checklist ) {
            $em->remove($checklist);
        }
        $o->clearChecklist();
        $em->flush();

        foreach($data['checklist_status'] as $id =>$status){
            $checklist = $settings->getChecklist($id);
            $ec = new ApplicationChecklist($o,$checklist);
            $ec->setStatus($status)
                ->setNotes($data['checklist_notes'][$id]);
            if($data['date_received'][$id] != null && $data['date_received'][$id] != ''){
                $ec->setDateReceived(new DateTime($data['date_received'][$id]));
            }
            $o->addChecklist($ec);

        }
        // $em->persist($profile);

        if(isset($data['result']) && $data['result'] == 'pass') {
            $this->url_append = "#tab_signing";
            $o->setStatus(Application::STATUS_SIGNING);
        }
        else if(isset($data['result']) && $data['result'] == 'fail') {
            $o->setStatus(Application::STATUS_FAILEDCHECKLIST);
        }
    }

    protected function updateSigning($o,$data,$is_new)
    {
        $this->url_append = "#tab_signing";
        $em = $this->getDoctrine()->getManager();
        if(isset($data['applicant_appeared']) && $data['applicant_appeared'] == 0) {
            $o->setAppeared(true);
            $o->setStatus(Application::STATUS_BLACKLIST);
        }
        else {

                $signing = array(
                    'date' => new DateTime($data['signing_date']),
                    'remark' => $data['signing_remark'],
                    );

                $o->setContractSigning($signing);

                if(isset($data['result']) && $data['result'] == 'pass') {
                    $o->setStatus(Application::STATUS_HIRED);
                }
                else if(isset($data['result']) && $data['result'] == 'fail') {
                    $o->setStatus(Application::STATUS_FAILEDSIGNING);
                }
        }
    }
    protected function updateProfile($o,$data,$is_new)
    {
        if($is_new)
        {
            $home_address = new Address;
            $permanent_address = new Address;
            $mphone = new Phone;
            $cphone = new Phone;  
            $pinfo = new ApplicationProfile();

            $home_address->setUserCreate($this->getUser()); 
            $permanent_address->setUserCreate($this->getUser());
            $mphone->setUserCreate($this->getUser());
            $cphone->setUserCreate($this->getUser());
            $pinfo->setUserCreate($this->getUser());
        }
        else
        {
            $pinfo = $o->getProfile();
            $home_address = $pinfo->getHomeAddress();
            $permanent_address = $pinfo->getPermanentAddress();
            $mphone = $pinfo->getMyNumber();
            $cphone = $pinfo->getContactNumber();
        }

        $em = $this->getDoctrine()->getManager();
        $cnt = $this->get('catalyst_contact');
        
        $choice = array(
            'first' => $data['first_choice'],
            'second' => $data['second_choice'],
            'third' => $data['third_choice']);
        $birth_date = new DateTime($data['birth_date']);
        
        // set applicant name and choices
        $o->setChoice($choice);
        $o->setFirstName($data['first_name']);
        $o->setMiddleName($data['middle_name']);
        $o->setLastName($data['last_name']);
        $o->setNickname($data['nickname']);
        $o->setEmailAddress($data['email_add']);

        // set applicant's personal information
        $pinfo->setGender($data['gender']);
        $pinfo->setBirthDate($birth_date);
        $pinfo->setBirthPlace($data['birth_place']);
        $pinfo->setHeight($data['height']);
        $pinfo->setWeight($data['weight']);

        $country_home =     $em->getRepository('HrisAdminBundle:WorldLocation')->find($data['home_country']);  
        $city_home =        $em->getRepository('HrisAdminBundle:WorldLocation')->find($data['home_city']);  
        $state_home =       $em->getRepository('HrisAdminBundle:WorldLocation')->find($data['home_state']);    

        // echo $city_home->getName();
        // exit;

        $home_address->setName($data['home_name'])
                     ->setStreet($data['home_street'])
                     ->setCity($city_home)
                     ->setState($state_home)
                     ->setCountry($country_home)
                     ->setIsPrimary(true);

        $country_perm =     $em->getRepository('HrisAdminBundle:WorldLocation')->find($data['permanent_country']);   
        $city_perm =        $em->getRepository('HrisAdminBundle:WorldLocation')->find($data['permanent_city']);  
        $state_perm =       $em->getRepository('HrisAdminBundle:WorldLocation')->find($data['permanent_state']);     

        $permanent_address->setName($data['permanent_name'])
                     ->setStreet($data['permanent_street'])
                     ->setCity($city_perm)
                     ->setState($state_perm)
                     ->setCountry($country_perm)
                     ->setIsPrimary(true);

        // set Contact Person and Number
        $pinfo->setContactPerson($data['contact_person']);
        
        //$mphone_type = $cnt->getPhoneType($data['myphone_type']);
        $mphone->setName($data['myphone_type']);
        $mphone->setNumber($data['my_number']);
        $mphone->setIsPrimary(true);

        $cphone->setName($data['contact_phone_type']);
        $cphone->setNumber($data['contact_number']);
        $cphone->setIsPrimary(true);

        $pinfo->setCivilStatus($data['civil_status']);
        $pinfo->setNumberOfDependents($data['dependent_num']);
        $pinfo->setSpouseName($data['spouse_name']);
        $pinfo->setNumberOfChildren($data['no_of_child']);

        $pinfo->setFatherName($data['father_name']);
        $pinfo->setFatherOccupation($data['father_occupation']);
        $pinfo->setMotherName($data['mother_name']);
        $pinfo->setMotherOccupation($data['mother_occupation']);

        $em->flush();
        if($is_new)
        {
            $em->persist($home_address);
            $em->persist($permanent_address);
            $em->persist($mphone);
            $em->persist($cphone);
            $em->flush();

            $pinfo->setHomeAddress($home_address);
            $pinfo->setPermanentAddress($permanent_address);     
            $pinfo->setMyNumber($mphone);
            $pinfo->setContactNumber($cphone);

            $o->addProfile($pinfo);
        }
        $this->url_append = "#tab_education";
    }
    protected function updateEducation($o,$data,$is_new)
    {
        if($o->getEducation() == null) {
            $educ_background = new ApplicationEducation;
            $educ_background->setUserCreate($this->getUser());    
        }
        else {
            $educ_background = $o->getEducation();
        }
        foreach ($data['id'] as $level) {
            $setter = 'set' . ucfirst($level);
            $array = array(
                'school_name_course' => $data[$level.'_name_course'],
                'year_from' => $data[$level.'_year_from'],
                'year_to' => $data[$level.'_year_to'],
                'awards_received' => $data[$level.'_awards_received']);
            $educ_background->$setter($array);
        }

        if($o->getEducation() == null)
        {
            $o->addEducation($educ_background);
        }

        $this->url_append = '#tab_experience';
    }

    protected function updateExperience($o,$data,$is_new)
    {
        $em = $this->getDoctrine()->getManager();
        // clear entries
        $works = $o->getExperience();
        foreach ($works as $work)
        $em->remove($work);
        $o->clearExperience();

        if (isset($data['company_detail'])){

            foreach ($data['company_detail'] as $index => $company_detail) {
                $work_exp = new ApplicationExperience();
                $work_exp->setUserCreate($this->getUser());
                $work_exp->setCompanyNameAddress($company_detail);
                $work_exp->setPositionHeld($data['position'][$index]);
                $work_exp->setEmploymentDuration($data['duration'][$index]);
                $work_exp->setStartingSalary($data['salary_start'][$index]);
                $work_exp->setLastSalary($data['salary_last'][$index]);
                $work_exp->setReason($data['reason'][$index]);

                $o->addExperience($work_exp); 
            }
        }
        $this->url_append = '#tab_skills'; 
    }

    protected function updateSkills($o,$data,$is_new)
    {
        $em = $this->getDoctrine()->getManager();
        $tech_skill = new ApplicationSkills;
        $tech_skill->setUserCreate($this->getUser());
        $comp_skills = array();
        $job_related_skills = array();
        $hobbies = array();
        
        // clear entries
        $skills = $o->getSkills();
        foreach ($skills as $skill)
        $em->remove($skill);
        $o->clearSkills();

        if(isset($data['computer']) && $data['computer'] != "")
        {
            foreach ($data['computer'] as $index => $skills) {
                if(!empty($skills)) {
                    $comp_skills[$index] = $skills;                
                }
            }
        }
        
        if(isset($data['related']) && $data['related'] !=  "")
        {
            foreach ($data['related'] as $index => $skills) {
                if(!empty($skills)) {
                    $job_related_skills[$index] = $skills;
                }
            } 
        }

        if(isset($data['hobbies']) && $data['hobbies'] != "")
        {
            foreach ($data['hobbies'] as $index => $skills) {
                if(!empty($skills)) {
                    $hobbies[$index] = $skills;
                }
            }
        }

        $tech_skill->setComputer($comp_skills);
        $tech_skill->setRelated($job_related_skills);
        $tech_skill->setHobbies($hobbies);

        $o->addSkills($tech_skill);

        $this->url_append = '#tab_reference';
    }

    protected function updateReference($o,$data,$is_new)
    {
        print_r($data);
        $em = $this->getDoctrine()->getManager();
        $cnt = $this->get('catalyst_contact');
        $names = array();

        $references = $o->getReference();
        foreach ($references as $reference) {
            $phone = $reference->getPhone();
            $em->remove($phone);
            $em->remove($reference);    
        }
        
        $o->clearReference();

        if(isset($data['name']) && $data['name'] != "")
        {
            foreach ($data['name'] as $index => $name) {  

                $char_ref = new ApplicationReference;
                $char_ref->setUserCreate($this->getUser()); 
                $phone = new Phone;
                $phone->setUserCreate($this->getUser());

                $char_ref->setSalutation($data['salutation'][$index]);
                $char_ref->setFirstName($data['fname'][$index]);
                $char_ref->setMiddleName($data['mname'][$index]);
                $char_ref->setLastName($data['lname'][$index]);
                $char_ref->setRelationship($data['relationship'][$index]);

                $phone->setName($data['contact_id'][$index]);
                $phone->setNumber($data['detail'][$index]);

                $phone->setIsPrimary(true);
                $em->persist($phone);
                $em->flush();
                $char_ref->setPhone($phone);

                $o->addReference($char_ref);  
            }
        }
        $this->url_append = '#tab_other_information';
    }

    protected function updateInformation($o,$data,$is_new)
    {
        $details = array();
        if($o->getInformation() == null) {
            $other_info = new ApplicationInformation();
            $other_info->setUserCreate($this->getUser());            
        }
        else {
            $other_info = $o->getInformation();
        }

        $other_info->setForcedResign($data['is_forced_resign']);
        $other_info->setCrimeConvicted($data['is_convicted']);
        $other_info->setSeriousDisease($data['has_serious_disease']);
        $other_info->setLicense($data['has_driver_license']);

        if(isset($data['is_forced_resign']) && $data['is_forced_resign'] == 1) {
            $details['forced_resign'] = $data['forced_resign_details'];    
        }
                
        if(isset($data['is_convicted']) && $data['is_convicted'] == 1) {
            $details['crime_convicted'] = $data['convicted_crime_details'];
        }

        if(isset($data['has_serious_disease']) && $data['has_serious_disease'] == 1) {
            $details['serious_disease'] = $data['has_serious_disease_details'];
        }

        if(isset($data['has_driver_license']) && $data['has_driver_license'] == 1) {
            $other_info->setLicenseType($data['is_professional']);      
        }
        
        $other_info->setSSSNumber($data['sss_number']);
        $other_info->setTinNumber($data['tin_number']);
        $other_info->setPhilHealthNumber($data['philhealth_number']);
        $other_info->setPagIbigNumber($data['pagibig_number']);
        $other_info->setData($details);

        if($o->getInformation() == null)
        $o->addInformation($other_info);

        $this->url_append = '#tab_upload';
    }

	protected function getObjectLabel($obj)
    {
        if ($obj == null)
        {
            return '';
        }
        return $obj->getDisplayName(); 
    }

    protected function newBaseClass() {
        return new Application();
    }

    protected function hookPostSave($obj, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->get('catalyst_configuration');
        $settings = $this->get('hris_settings');
        if ($config->get('hris_hr_department') == null) 
        {
            $hr = null;
        }
        else
        {
            $hr = $settings->getDepartment($config->get('hris_hr_department'));
            $hr->getDeptHead();
        }
        
        $wf = $this->get('hris_workforce');
        $manpower = $this->get('hris_recruitment');

        $user = $this->getUser();
        if($obj->getStatus() == Application::STATUS_HIRED)
        {
            $wf->copyApplicant($obj,$user);
            $manpower->ManpowerRequestCount($obj);
            if ($hr != null) 
            {

                $event = new NotificationEvent();
                $event->notify(array(
                    'source'=> 'Applicant hired',
                    'link'=> $this->generateUrl('hris_applications_edit_form',array('id'=>$obj->getID())),
                    'message'=> 'Applicant '.$obj->getFirstName().' '.$obj->getLastName().' has been hired.',
                    'type'=> Notification::TYPE_UPDATE,
                    'receipient' => $hr));

                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch('notification.event', $event);
            }
        }
        elseif ($obj->getStatus() == Application::STATUS_INTERVIEW) 
        {
            $result =  $obj->getInterview();
                
            if ($result != '' || $result != null) 
            {

                $result =  $obj->getInterview()->getResult();
                
                foreach ($result as $value) 
                {
                    $emp_id = json_decode($value['id']);
                    $emp    = $em->getRepository('HrisWorkforceBundle:Employee')->find($emp_id);

                    if ($emp != null) 
                    {
                        $event = new NotificationEvent();
                        $event->notify(array(
                            'source'=> 'An interview has been assigned to you',
                            'link'=> $this->generateUrl('hris_applications_edit_form',array('id'=>$obj->getID())),
                            'message'=> 'You are assigned to interview '.$obj->getFirstName().' '.$obj->getLastName().' on '.$obj->getInterview()->getDate()->format('F m Y'),
                            'type'=> Notification::TYPE_UPDATE,
                            'receipient' => $emp));

                        $dispatcher = $this->get('event_dispatcher');
                        $dispatcher->dispatch('notification.event', $event);
                    }
                }
            }
        }

        
        if($is_new)
        {          
            $event = new NotificationEvent();
            $event->notify(array(
                'source'=> 'New applicant added',
                'link'=> $this->generateUrl('hris_applications_edit_form',array('id'=>$obj->getID())),
                'message'=> 'Applicant '.$obj->getFirstName().' '.$obj->getLastName().' added.',
                'type'=> Notification::TYPE_UPDATE,
                'receipient' => $hr));

            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('notification.event', $event);
        }
    }

    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');
        return array(
            $grid->newColumn('Applicant Name', 'getDisplayName', 'last_name'),
            $grid->newColumn('Status', 'getStatusFormatted', 'status_id'),
        );
    }

    public function ajaxFilterVacancyAction()
    {
        $data = $this->getRequest()->query->all();
        $em = $this->getDoctrine()->getManager();
        $recruitment = $this->get('hris_recruitment');
        $query = $data['query'];
        
        $vacancy = $recruitment->getVacantPositions();

        
        $list_opts = [];
        foreach ($vacancy as $job) {

            if(strstr(strtolower($job->getName()),strtolower($query)) !== FALSE){
            $list_opts[] = array('id'=>$job->getID(), 'name'=> $job->getName());
            }
        }
        return new JsonResponse($list_opts);
    }

    protected function validate($data, $type)
    {
        $session = $this->getRequest()->getSession();
        if($data['csrf_token'] != $session->get('csrf_token')){
            throw new ValidationException('Duplicate Request');
        }
        $session->set('csrf_token', '');

        if (isset($data['birth_date'])) 
        {
            $today = new DateTime();
            $set = new DateTime($data['birth_date']);
            $result = date_diff($today, $set);
            $result = intval($result->format("%R%a"));
        }
        else
        {
            
        }
        

        if($type == 'add')
        {
            if ($result >= 0) 
            {
                throw new ValidationException('Date of birth cannot be set in the future');
            }

            if(empty($data['first_name']))
            {
                throw new ValidationException('Cannot leave First Name blank');
            }

            if(empty($data['last_name']))
            {
                throw new ValidationException('Cannot leave Last Name blank');
            }

            if(empty($data['civil_status']))
            {
                throw new ValidationException('Please Select Civil Status.');
            }

            // if(empty($data['permanent_name']))
            // {
            //     throw new ValidationException('Cannot leave Unit/Number blank.');
            // }

            // if(empty($data['permanent_name']))
            // {
            //     throw new ValidationException('Cannot leave Unit/Number blank.');
            // }

            // if(empty($data['permanent_street']))
            // {
            //     throw new ValidationException('Cannot leave Street Address blank.');
            // }

            // if(empty($data['permanent_city']))
            // {
            //     throw new ValidationException('Cannot leave City blank.');
            // }
        }
    }

    protected function getExamProgressClass($status)
    {
        switch($status)
        {
            case Application::STATUS_EXAM : return 'progtrckr-current';
            break;
            case Application::STATUS_BLACKLISTEXAM : return 'progtrckr-fail';
            break;
            case Application::STATUS_FAILEDEXAM : return 'progtrckr-fail';
            break;
            default :return 'progtrckr-done';
        }
    }

    protected function getInterviewProgressClass($status)
    {
        switch($status)
        {
            case Application::STATUS_EXAM : return 'progtrckr-todo';
            break;
            case Application::STATUS_BLACKLISTEXAM : return 'progtrckr-todo';
            break;
            case Application::STATUS_FAILEDEXAM : return 'progtrckr-todo';
            break;
            case Application::STATUS_INTERVIEW : return 'progtrckr-current';
            break;
            case Application::STATUS_BLACKLISTINTERVIEW: return 'progtrckr-fail';
            break;
            case Application::STATUS_FAILEDINTERVIEW : return 'progtrckr-fail';
            break;
            default :return 'progtrckr-done';
        }
    }

    protected function getCheckProgressClass($status)
    {
        switch($status)
        {
            case Application::STATUS_EXAM : return 'progtrckr-todo';
            break;
            case Application::STATUS_BLACKLISTEXAM : return 'progtrckr-todo';
            break;
            case Application::STATUS_FAILEDEXAM : return 'progtrckr-todo';
            break;
            case Application::STATUS_INTERVIEW : return 'progtrckr-todo';
            break;
            case Application::STATUS_BLACKLISTINTERVIEW: return 'progtrckr-todo';
            break;
            case Application::STATUS_FAILEDINTERVIEW : return 'progtrckr-todo';
            break;
            case Application::STATUS_CHECK : return 'progtrckr-current';
            break;
            case Application::STATUS_FAILEDCHECK : return 'progtrckr-fail';
            break;
            default :return 'progtrckr-done';
        }
    }

    protected function getOfferProgressClass($status)
    {
        switch($status)
        {
            case Application::STATUS_EXAM : return 'progtrckr-todo';
            break;
            case Application::STATUS_BLACKLISTEXAM : return 'progtrckr-todo';
            break;
            case Application::STATUS_FAILEDEXAM : return 'progtrckr-todo';
            break;
            case Application::STATUS_INTERVIEW : return 'progtrckr-todo';
            break;
            case Application::STATUS_BLACKLISTINTERVIEW: return 'progtrckr-todo';
            break;
            case Application::STATUS_FAILEDINTERVIEW : return 'progtrckr-todo';
            break;
            case Application::STATUS_CHECK : return 'progtrckr-todo';
            break;
            case Application::STATUS_FAILEDCHECK : return 'progtrckr-todo';
            break;
            case Application::STATUS_OFFER : return 'progtrckr-current';
            break;
            case Application::STATUS_BLACKLISTOFFER : return 'progtrckr-fail';
            break;
            case Application::STATUS_FAILEDOFFER : return 'progtrckr-fail';
            break;
            default :return 'progtrckr-done';
        }
    }

    protected function getChecklistProgressClass($status)
    {
        switch($status)
        {
            case Application::STATUS_EXAM : return 'progtrckr-todo';
            break;
            case Application::STATUS_BLACKLISTEXAM : return 'progtrckr-todo';
            break;
            case Application::STATUS_FAILEDEXAM : return 'progtrckr-todo';
            break;
            case Application::STATUS_INTERVIEW : return 'progtrckr-todo';
            break;
            case Application::STATUS_BLACKLISTINTERVIEW: return 'progtrckr-todo';
            break;
            case Application::STATUS_FAILEDINTERVIEW : return 'progtrckr-todo';
            break;
            case Application::STATUS_CHECK : return 'progtrckr-todo';
            break;
            case Application::STATUS_FAILEDCHECK : return 'progtrckr-todo';
            break;
            case Application::STATUS_OFFER : return 'progtrckr-todo';
            break;
            case Application::STATUS_BLACKLISTOFFER : return 'progtrckr-todo';
            break;
            case Application::STATUS_FAILEDOFFER : return 'progtrckr-todo';
            break;
            case Application::STATUS_CHECKLIST : return 'progtrckr-current';
            break;
            case Application::STATUS_FAILEDCHECKLIST : return 'progtrckr-fail';
            break;
            default :return 'progtrckr-done';
        }
    }
    protected function getSignProgressClass($status)
    {
        switch($status)
        {
            case Application::STATUS_EXAM : return 'progtrckr-todo';
            break;
            case Application::STATUS_BLACKLISTEXAM : return 'progtrckr-todo';
            break;
            case Application::STATUS_FAILEDEXAM : return 'progtrckr-todo';
            break;
            case Application::STATUS_BLACKLISTINTERVIEW: return 'progtrckr-todo';
            break;
            case Application::STATUS_INTERVIEW : return 'progtrckr-todo';
            break;
            case Application::STATUS_FAILEDINTERVIEW : return 'progtrckr-todo';
            break;
            case Application::STATUS_CHECK : return 'progtrckr-todo';
            break;
            case Application::STATUS_FAILEDCHECK : return 'progtrckr-todo';
            break;
            case Application::STATUS_OFFER : return 'progtrckr-todo';
            break;
            case Application::STATUS_BLACKLISTOFFER : return 'progtrckr-todo';
            break;
            case Application::STATUS_FAILEDOFFER : return 'progtrckr-todo';
            break;
            case Application::STATUS_CHECKLIST : return 'progtrckr-todo';
            break;
            case Application::STATUS_FAILEDCHECKLIST : return 'progtrckr-todo';
            break;
            case Application::STATUS_SIGNING : return 'progtrckr-current';
            break;
            case Application::STATUS_FAILEDSIGNING : return 'progtrckr-fail';
            break;
            default :return 'progtrckr-done';
        }
    }

    protected function getChecklistIds($object)
    {
        $checklist = $object->getChecklist();
        $list = [];
        foreach ($checklist as $entry) {
            $list[$entry->getChecklist()->getID()] = $entry;
        }
        return $list;
    }
    protected function padFormParams(&$params, $object = NULL){
        
        $em = $this->getDoctrine()->getManager();
        $cnt = $this->get('catalyst_contact');
        $settings = $this->get('hris_settings');
        $recruitment = $this->get('hris_recruitment');
        $payroll = $this->get('hris_payroll');

        $params['paytype_opts'] = $payroll->getPayPeriodOptions();
        $params['paysched_opts'] = $payroll->getPayScheduleOptions();

        $params['status_exam'] = $this->getExamProgressClass($object->getStatus());
        $params['status_interview'] = $this->getInterviewProgressClass($object->getStatus());
        $params['status_offer'] =  $this->getOfferProgressClass($object->getStatus());
        $params['status_check'] =  $this->getCheckProgressClass($object->getStatus());
        $params['status_checklist'] = $this->getChecklistProgressClass($object->getStatus());
        $params['status_sign'] =  $this->getSignProgressClass($object->getStatus());
        $params['gender_opts'] = array('Male' => 'Male', 'Female' => 'Female');

        $params['position_opts'] = $recruitment->getVacantPositions();
        $params['sched_opts'] = $settings->getSchedulesOptions();
        $params['jobtitle_opts'] = $settings->getJobTitleOptions();
        $params['level_opts'] = $settings->getJobLevelOptions();
        $params['location_opts'] = $settings->getLocationOptions();
        $params['employment_status_opts'] = $settings->getEmploymentStatusOptions();
        $params['job_level_opts'] = array(
            'Regular' => 'Regular',
            'Probationary' => 'Probationary',
            'Contractual' => 'Contractual',
            );
        $params['employment_opts'] = array(
            'Managerial' => 'Managerial',
            'Supervisory' => 'Supervisory',
            'Clerical' => 'Clerical',
            );
        $params['reputation_opts'] = array(
            'Bad' => 'Bad',
            'Good' => 'Good',
            'Extravagant' => 'Extravagant',
            );
        $params['phone_type_opts'] = $cnt->getPhoneTypeOptions();
        $params['exam_opts'] = array(
            'judgement_comprehension' => 'Judgement and Comprehension',
            'iq_test' => 'IQ Test',
            'typing_test' => 'Typing Test',
            'essay' => 'Essay',
            );
        $params['interview_opts'] = array(
            'Reject' => 'Reject',
            'Hold for Comparisons' => 'Hold for Comparisons',
            'Recommended for further Interview' => 'Recommended for further Interview',
            'Recommended for the position applied' => 'Recommended for the position applied'
            );

        $em = $this->getDoctrine()->getManager();
        $filter = array();
        $countries = $em
            ->getRepository('HrisAdminBundle:WorldLocation')
            ->findBy(
                array('parent' => 0)
            );

        $country_opts = array();
        foreach ($countries as $country)
            $country_opts[$country->getID()] = $country->getName();

        $params['country_opts'] = $country_opts;

        if ($object->getProfile() != null) 
        {
            if ($object->getProfile()->getHomeAddress() != null) 
            {
                $state_opts = array();
                $state_opts[$object->getProfile()->getHomeAddress()->getState()->getID()] = $object->getProfile()->getHomeAddress()->getState()->getName();
                $params['home_state_opts'] = $state_opts;

                $city_opts = array();
                $city_opts[$object->getProfile()->getHomeAddress()->getCity()->getID()] = $object->getProfile()->getHomeAddress()->getCity()->getName();
                $params['home_city_opts'] = $city_opts;
            }
            else
            {
                $params['home_state_opts'] = '';
                $params['home_city_opts'] = '';
            }

            if ($object->getProfile()->getPermanentAddress() != null) 
            {
                $state_opts = array();
                $state_opts[$object->getProfile()->getPermanentAddress()->getState()->getID()] = $object->getProfile()->getPermanentAddress()->getState()->getName();
                $params['permanent_state_opts'] = $state_opts;

                $city_opts = array();
                $city_opts[$object->getProfile()->getPermanentAddress()->getCity()->getID()] = $object->getProfile()->getPermanentAddress()->getCity()->getName();
                $params['permanent_city_opts'] = $city_opts;
            }
            else
            {
                $params['permanent_state_opts'] = '';
                $params['permanent_city_opts'] = '';
            }
        }
        else
        {
            $params['permanent_state_opts'] = '';
            $params['permanent_city_opts'] = '';
            $params['home_state_opts'] = '';
            $params['home_city_opts'] = '';
        }
    

        $params['exam_id'] = 0;
        $params['status_id'] = 0;
        //$params['int_opts'] = $emp_opts;
        $params['status_opts'] = array(
            'Failed' => 'Failed',
            'Passed' => 'Passed'
            );  
        if($object->getID() != null){
            $params['checklist_status_opts'] = array('Pending'=>'Pending','Received'=>'Received');
            $params['checklists'] = $this->getChecklistIds($object);
            $params['checklist_opts'] = $settings->getChecklists();
        }
        return $params;
    }

    public function callbackGrid($id)
    {
        $params = array(
            'id' => $id,
            'route_edit' => $this->getRouteGen()->getEdit(),
            'route_delete' => $this->getRouteGen()->getDelete(),
            'route_progress' => $this->generateUrl('hris_applications_edit_progress_form',array('id'=>$id)),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'HrisRecruitmentBundle:Application:action.html.twig',
            $params
        );
    }

    public function printAppAction($id)
    {
        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $twig = "HrisRecruitmentBundle:Application:print.html.twig";

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

        $params['request'] = $em->getRepository('HrisRecruitmentBundle:Application')->find($id);

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

        $obj = $em->getRepository('HrisRecruitmentBundle:Application')->find($id);
        $params['object'] = $obj;


        $pdf = $this->get('catalyst_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }

    public function getChildLocationAction($parent_id)
    {
        $em = $this->getDoctrine()->getManager();
        $filter = array();
        $countries = $em
            ->getRepository('HrisAdminBundle:WorldLocation')
            ->findBy(
                array('parent' => $parent_id)
            );

        $country_opts = array();

        foreach ($countries as $country)
        {
            $country_opts[] = 
            [
                'id' => $country->getID(),
                'text' => $country->getName(),
            ];
        }


        return new JsonResponse($country_opts);   

    }

}