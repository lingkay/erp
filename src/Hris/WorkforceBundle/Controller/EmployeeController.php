<?php

namespace Hris\WorkforceBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Hris\WorkforceBundle\Entity\Employee;
use Hris\WorkforceBundle\Entity\EmployeeChecklist;
use Hris\WorkforceBundle\Entity\EmployeeBenefits;
use Hris\WorkforceBundle\Entity\EmployeeLeaves;
use Gist\ContactBundle\Entity\Address;
use Gist\UserBundle\Entity\User;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\ContactBundle\Template\Controller\HasPhones;

use DateTime;

class EmployeeController extends CrudController
{
    use TrackCreate;
    use HasPhones;

	public function __construct()
	{
		$this->route_prefix = 'hris_workforce_employee';
		$this->title = 'Employee';

		$this->list_title = 'Employees';
		$this->list_type = 'dynamic';
        $this->submit_redirect = false;
	}

    protected function newBaseClass() 
    {
        return new Employee();
    }

    protected function getObjectLabel($obj) 
    {
        if ($obj == null){
            return '';
        }
        return $obj->getDisplayName();
    }  

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Employee Name', 'getDisplayName', 'last_name'),
            $grid->newColumn('Job Title', 'getName', 'name','j'),
            $grid->newColumn('Employment Status', 'getEmploymentStatus','employment_status'),
            $grid->newColumn('Department', 'getName', 'name','d'),
            $grid->newColumn('Immediate Supervisor', 'getDisplayName', 'last_name','e'),
        );
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('j', 'job_title', 'getJobTitle'),
            $grid->newJoin('d', 'department', 'getDepartment'),
            $grid->newJoin('e', 'supervisor', 'getSupervisor','left'),
        );
    }

    protected function update($o, $data, $is_new = false){

        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        switch($data['employee_form']){
            case 'info':
                $this->updateInfo($o, $data, $is_new);
                break;
            case 'benefits':
                $this->updateBenefits($o, $data, $is_new);
                break;
            case 'checklist':
                $this->updateChecklist($o,$data, $is_new);
                break;
            case 'newchecklist':
                $this->updateNewChecklist($o,$data, $is_new);
                break;
            case 'profile':
                $this->updateProfile($o,$data, $is_new);
                break;
            case 'account':
                $this->updateAccount($o,$data, $is_new);
                break;
            case 'dependents':
                $this->updateDependents($o,$data, $is_new);
                break;
            case 'cashbond':
                $this->updateCashbond($o,$data, $is_new);
                break;
        }

        $this->updateTrackCreate($o, $data, $is_new);

    }

    protected function updateInfo($o, $data, $is_new)
    {
        $em = $this->getDoctrine()->getManager();
        //test if employee id already exists
        $result = $em->getRepository('HrisWorkforceBundle:Employee')->findBy(array('employee_code' => $data['employee_id']));
        if (count($result) == 0 || $o->getEmployeeId() == $data['employee_id']) 
        {
            $o->setEmployeeId($data['employee_id']);
        }
        else
        {
            $this->addFlash('error', "Cannot update employee ID when entered ID already exists!");
        }
        $this->url_append = "#tab_info";
        $profile = $o->getProfile();
        $settings = $this->get('hris_settings');
        $payroll = $this->get('hris_payroll');

        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();

        $emp_ben = $em->getRepository('HrisWorkforceBundle:EmployeeBenefits')->findBy(
                array('employee' => $o->getID())
            );

        foreach ($emp_ben as $entry){
            $em->remove($entry);
        }

        $o->setFirstName($data['first_name']);
        $o->setMiddleName($data['middle_name']);
        $o->setLastName($data['last_name']);
        $o->setGender($data['gender']);

        $o->setDepartment($settings->getDepartment($data['department']));
        $o->setJobTitle($settings->getJobTitle($data['job_title']));
        $o->setJobLevel($settings->getJobLevel($data['job_level']));
        $o->setLocation($settings->getLocation($data['location']));
        $o->setEmploymentStatus($data['employment_status']);
        $o->setSchedule($settings->getSchedule($data['schedule']));
        $o->setPayRate($data['pay_rate']);
        $o->setPaySchedule($payroll->getPayType($data['pay_sched']));
        $o->setPayPeriod($payroll->getPayType($data['pay_type']));
        $o->setSupervisor($wf->getEmployee($data['supervisor_id']));
        $o->setExemption(isset($data['exemption']));
        $o->setDateHired(new DateTime($data['date_hired']));

        $o->setEmail($data['email']);
        
        if($is_new){
            $o->setProfile($wf->generateProfile($o));
            $user = $wf->generateUser($o);
            $o->setUser($user);
            $this->sendRegistrationEmail($user);
        }else {
            $wf->checkSalaryChange($o);
            $user = $o->getUser();
            $user->setEmail($data['email']);
            $em->persist($user);
            $em->flush();
        }
    }

    protected function updateBenefits($o, $data, $is_new)
    {
        $this->url_append = "#tab_benefits";

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();

        $em = $this->getDoctrine()->getManager();

        $emp_ben = $em->getRepository('HrisWorkforceBundle:EmployeeBenefits')->findBy(
                array('employee' => $o->getID())
            );

        $benefit = [];
        $leave = [];

        foreach ($emp_ben as $ben) {
            $em->remove($ben);
        }
        $em->flush();
        if(isset($data['benefit'])){
            foreach ($data['benefit'] as $id => $ben) {
                // print_r($ben);
                $emp_ben = new EmployeeBenefits();
                $data = explode("-", $ben);
                // print_r($data[1]);

                if ($data[0] == 'benefit') {
                    if (!in_array($data[1], $benefit)) {
                        $b = $em->getRepository('HrisAdminBundle:Benefit')->find($data[1]);
                        $emp_ben->setEmployee($o)
                            ->setBenefit($b);
                        $em->persist($emp_ben);
                    }
                }
                else {
                    if (!in_array($data[1], $leave)) {
                        $l = $em->getRepository('HrisAdminBundle:Leave\LeaveType')->find($data[1]);
                        $emp_ben->setEmployee($o)
                            ->setLeave($l);
                        $em->persist($emp_ben);

                        $existing = $em->getRepository('HrisWorkforceBundle:EmployeeLeaves')->findOneBy(
                                array(
                                    'employee' => $o,
                                    'leave_type' => $l
                                )
                            );
                        if ($existing == NULL or $existing == '') {
                            $emp_leave = new EmployeeLeaves();

                            $emp_leave->setEmployee($o);
                            $emp_leave->setLeaveType($l);
                            $emp_leave->setAvailLeaves($l->getLeaveCount());
                            $emp_leave->setLeaveYear(date('Y'));

                            $em->persist($emp_leave);
                        }
                    }
                }
            }
        } 
    }


    protected function updateChecklist($o, $data, $is_new){
        $this->url_append = "#tab_checklist";
        $profile = $o->getProfile();
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');

        //Clear checklist
        foreach ($profile->getChecklist() as $checklist ) {
            $em->remove($checklist);
        }
        $profile->clearChecklist();
        $em->flush();

        foreach($data['checklist_status'] as $id =>$status){
            $checklist = $settings->getChecklist($id);
            $ec = new EmployeeChecklist($profile,$checklist);
            $ec->setStatus($status)
                ->setNotes($data['checklist_notes'][$id]);
            if($data['date_received'][$id] != null && $data['date_received'][$id] != ''){
                $ec->setDateReceived(new DateTime($data['date_received'][$id]));
            }
            $profile->addChecklist($ec);

        }
        $em->persist($profile);
      
    }

    protected function updateProfile($o, $data, $is_new){
        $this->url_append = "#tab_profile";
        $profile = $o->getProfile();
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');
        $media = $this->get('gist_media');

        $profile->setSss($data['sss']);
        $profile->setTin($data['tin']);
        $profile->setPagibig($data['pagibig']);
        $profile->setPhilhealth($data['philhealth']);
        $profile->setBankAccount($data['bank_account']);
        $profile->setBirthday(new DateTime($data['birthday']));
        if($data['picture']!=0 && $data['picture'] != ""){
            $profile->setUpload($media->getUpload($data['picture']));
        }

        $profile->setAddress($this->updateAddress($o,$data,$is_new));
        $this->updateHasPhones($profile,$data,$is_new);
        $em->persist($profile);
    }

    protected function updateDependents($o, $data, $is_new){
        $this->url_append = "#tab_dependents";
        $em = $this->getDoctrine()->getManager();
        $dependents = array();

        if(isset($data['dependent_name']))
        {
            foreach ($data['dependent_name'] as $index => $value) {
                if(isset($data[$value.'_qualified']))
                {
                    $qualified = $data[$value.'_qualified'];
                }

                $dependents[$index] = array(
                    'name' => $value,
                    'relationship' => $data['dependent_relationship'][$index],
                    'birthdate' => $data['dependent_birthdate'][$index],
                    'remarks' => $data['dependent_remarks'][$index],
                    'qualified' => $qualified
                    );  
            }  
        }
        
        $o->setDependents($dependents);
        $o->setMaritalStatus($data['civil']);
    }

    protected function updateAccount($o, $data, $is_new)
    {
        $this->url_append = "#tab_account";
        $em = $this->getDoctrine()->getManager();
        $user = $o->getUser();
        $um = $this->container->get('fos_user.user_manager');

        //Update password if not empty
        if(($data['pass1']== $data['pass2']) && $data['pass1'] != ""){
            
            $user->setPlainPassword($data['pass1']);
            $um->updatePassword($user);
        }

        $user->setEnabled($data['enabled']);
        $user->setEmailNotify($data['email_notify']);
        $em->persist($user);
    }


    protected function updateCashbond($o, $data, $is_new)
    {
        $this->url_append = "#tab_cashbond";
        $o->setCashbondRate($data['cashbond_rate']);
    }

    protected function updateAddress($o,$data,$is_new)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $this->get('gist_contact');

        if($data['address_id'] == 0 || $data['address_id'] == ""){
            $address = $contact->newAddress();
        }else {
            $address = $contact->getAddress($data['address_id']);
        }

        if (isset($data['country']) && $data['country'] != 0) 
        {
            $country = $em->getRepository('HrisAdminBundle:WorldLocation')->find($data['country']);   
        }
        else
        {
            $country = null;
        }

        if (isset($data['city']) && $data['city'] != 0) 
        {
            $city =    $em->getRepository('HrisAdminBundle:WorldLocation')->find($data['city']);  
        }
        else
        {
            $city = null;
        }

        if (isset($data['state']) && $data['state'] != 0) 
        {
            $state =   $em->getRepository('HrisAdminBundle:WorldLocation')->find($data['state']); 
        }   
        else
        {
            $state = null;
        }

        $address->setName($data['unit'])
                ->setStreet($data['street'])
                ->setState($state)
                ->setCity($city)
                ->setCountry($country)
                ->setLongitude($data['longitude'])
                ->setLatitude($data['latitude']);
        $em->persist($address);
        
        if($is_new){
            $em->flush();
        }
        return $address;
    }

    protected function sendRegistrationEmail(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $config  = $this  ->get('gist_configuration');

        if ($config->get('hris_com_info_company_name') != null) 
        {
            $company_name = $config->get('hris_com_info_company_name');
        }
        else
        {
            $company_name = "Quadrant Alpha Technology Solutions, Inc.";
        }

        if ($config->get('hris_com_info_website') != null) 
        {
            $company_website = $config->get('hris_com_info_website');
        }
        else
        {
            $company_website = "www.quadrantalpha.com";
        }

        if ($config->get('hris_com_info_company_address') != null) 
        {
            $company_address = $em->getRepository('GistContactBundle:Address')->find($config->get('hris_com_info_company_address'));
        }
        else
        {
            $company_address = "Unit 102 10th Floor, Legaspi Suites, Salcedo Street, Legaspi Village, Makati City";
        }

        $media = $this->get('gist_media');
        if ($config->get('hris_com_logo') != '') 
        {
            $path = $media->getUpload($config->get('hris_com_logo'));

            $str = $path->getURL();
            $str = parse_url($str, PHP_URL_PATH);
            $str = ltrim($str, '/');

            $logo = $str;
        }
        else
        {
            $logo = '';
        }

        $mailer = $this->get('mailer');
        $message = $mailer->createMessage()
            ->setSubject('You have Completed Registration!')
            ->setFrom('developer@quadrantalpha.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'HrisWorkforceBundle:Employee:registration_email.html.twig',
                    array('name'=> $user->getName(),
                        'username' => $user->getUsername(),
                        'password'=>$user->getPlainPassword(),
                        'company_name'=>$company_name,
                        'company_address'=>$company_address,
                        'company_website'=>$company_website,
                        'logo'=>$logo)
                ),
                'text/html'
            );
        $mailer->send($message);
    }

    protected function getBenefits($benefits,$leaves,$object)
    {
        $list = array();
        foreach ($benefits as $e) {
            if(in_array($object->getEmploymentStatus(), $e->getEmpStatus()) &&
                in_array($object->getGender(), $e->getGender()) && 
                in_array($object->getDepartment()->getID(), $e->getDepartment())){
                $list['benefit-'.$e->getID()] = $e->getName();
            }
        }

        foreach ($leaves as $e) {
            if(in_array($object->getEmploymentStatus(), $e->getEmpStatus()) &&
                in_array($object->getGender(), $e->getGender())){
                $list['leave-'.$e->getID()] = $e->getName();
            }
        }

        return $list;
    }

    protected function getAppraisalResults($appraisal)
    {
        $a = [];
        $am = $this->get('hris_appraisal');

        foreach ($appraisal as $app) {
            $a[$app->getID()]['id'] = $app->getID();
            $a[$app->getID()]['grade'] = $am->getAppFinalGrade($app->getID());
            $a[$app->getID()]['rating'] = $am->getAppFinalRating($app->getID());
        }

        return $a;
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();

        $this->padFormPhoneType($params);

        $benefits = $em->getRepository('HrisAdminBundle:Benefit')->findAll();
        $leaves = $em->getRepository('HrisAdminBundle:Leave\LeaveType')->findAll();
        $emp_ben = $em->getRepository('HrisWorkforceBundle:EmployeeBenefits')->findBy(
                array('employee' => $object->getID())
            );
        $emp_mem = $em->getRepository('HrisMemoBundle:Memo')->findBy(
                array('employee' => $object->getID())
            );
        $appraisal = $em->getRepository('HrisWorkforceBundle:Appraisal')->findBy(
                array('employee' => $object->getID())
            );

        $evals = array();
        foreach ($appraisal as $a) {
            $evals[$a->getID()] = $em->getRepository('HrisWorkforceBundle:Evaluator')->findBy(
                    array('appraisal' => $a->getID())
                );
        }

        $benefit_list = $this->getBenefits($benefits,$leaves,$object);

        $settings = $this->get('hris_settings');
        $payroll = $this->get('hris_payroll');
        $wm = $this->get('hris_workforce');

        $params['relationship_opts'] = [
            'Parent' => "Parent",
            'Sibling' => "Sibling",
            'Child' => "Child"
        ];
        $params['dept_opts'] = $settings->getDepartmentOptions();
        $params['title_opts'] = $settings->getJobTitleOptions();
        $params['level_opts'] = $settings->getJobLevelOptions();
        $params['status_opts'] = $settings->getEmploymentStatusOptions();
        $params['location_opts'] = $settings->getLocationOptions();
        $params['payroll'] = $payroll->getEmployeePayrolls($object);
        $params['account_opts'] = array(1 => 'Enabled', 0=>'Disabled');
        $params['sendmail_opts'] = array(1 => 'Yes', 0=>'No');
        
        $params['relationship_opts'] = array('Parent' => 'Parent','Sibling' => 'Sibling','Child' => 'Child');
        $params['condition_opts'] = array(' ' => 'Please Select','Living Along with Employee' => 'Living Along with Employee','Mentally Challenged' => 'Mentally Challenged', 'Physically Challenged' => 'Physically Challenged');
        $params['sched_opts'] = $settings->getSchedulesOptions();
        $params['benefits'] = $benefit_list;
        $params['emp_ben'] = $emp_ben;
        $params['emp_mem'] = $emp_mem;
        $params['appr'] = $appraisal;
        $params['appr_result'] = $this->getAppraisalResults($appraisal);
        $params['evals'] = $evals;
        $params['gender_opts'] = array('Male' => 'Male', 'Female' => 'Female');
        $params['hasSavings'] = $wm->hasEmployeeBenefit($object, 'Savings');
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
            if ($object->getProfile()->getAddress() != null) 
            {
                $state_opts = array();
                if ($object->getProfile()->getAddress()->getState() != null) 
                {
                    $em = $this->getDoctrine()->getManager();
                    $filter = array();
                    $states = $em
                        ->getRepository('HrisAdminBundle:WorldLocation')
                        ->findBy(
                            array('parent' => $object->getProfile()->getAddress()->getCountry()->getID())
                        );
                    foreach ($states as $state)
                    {
                        $state_opts[$state->getID()] = $state->getName();
                    }
                    $params['state_opts'] = $state_opts;
                }
                else
                {
                    $params['state_opts'] = '0';
                }

                $city_opts = array();
                if ($object->getProfile()->getAddress()->getCity() != null) 
                {
                    $em = $this->getDoctrine()->getManager();
                    $filter = array();
                    $cities = $em
                        ->getRepository('HrisAdminBundle:WorldLocation')
                        ->findBy(
                            array('parent' => $object->getProfile()->getAddress()->getState()->getID())
                        );
                    foreach ($cities as $city)
                    {
                        $city_opts[$city->getID()] = $city->getName();
                    }
                    $params['city_opts'] = $city_opts;
                }
                else
                {
                    $params['city_opts'] = '0';
                }
            }
            else
            {
                $params['state_opts'] = '';
                $params['city_opts'] = '';
            }
        }
        else
        {
            $params['state_opts'] = '';
            $params['city_opts'] = '';
        }


        $params['paytype_opts'] = $payroll->getPaySchedOptions();
        $params['paysched_opts'] = $payroll->getPaySchedOptions();
        $params['civil_opts'] = array(Employee::CIVIL_SINGLE => Employee::CIVIL_SINGLE,
                                    Employee::CIVIL_MARRIED => Employee::CIVIL_MARRIED,
                                    Employee::CIVIL_WIDOWED => Employee::CIVIL_WIDOWED,
                                    Employee::CIVIL_SEPARATED => Employee::CIVIL_SEPARATED,
                                    Employee::CIVIL_DIVORCED => Employee::CIVIL_DIVORCED);
        if($object->getID() != null){
            $params['checklist_status_opts'] = array('Pending'=>'Pending','Received'=>'Received');
            $params['checklists'] = $this->getChecklistIds($object);
            $params['checklist_opts'] = $settings->getChecklists();
        }

        $cb = $this->get('hris_cashbond');
        $params['cashbond'] = $cb->findCashbond($object);

        if ($this->getUser()->getEmployee() != null) 
            {
                //check if hr
                $groups = $this->getUser()->getGroups();
                foreach ($groups as $group) 
                {
                    if ($group->getName() == "hr_admin") 
                    {
                        $params['hr'] = 'true';
                        break;
                    }
                    else
                    {
                        $params['hr'] = 'false';
                    }
                }
                
            }
            else
            {
                $params['hr'] = 'false';
                $params['dh'] = 'false';
                $params['vp'] = 'false';
            }

        return $params;
    }

    protected function getChecklistIds($object)
    {
        $checklist = $object->getProfile()->getChecklist();
        $list = [];
        foreach ($checklist as $entry) {
            $list[$entry->getChecklist()->getID()] = $entry;
        }
        return $list;
    }

    public function ajaxFilterEmployeeAction()
    {
        $data = $this->getRequest()->query->all();
        $em = $this->getDoctrine()->getManager();
        $query = $data['query'];

        $employees = $em->getRepository("HrisWorkforceBundle:Employee")->createQueryBuilder('o')
           ->where('o.first_name LIKE :first_name')
           ->orWhere('o.last_name LIKE :last_name')
           ->setParameter('first_name', "%".$query."%")
           ->setParameter('last_name', "%".$query."%")
           ->getQuery()
           ->getResult();

        $list_opts = [];
        foreach ($employees as $employee) {
            $list_opts[] = array('id'=>$employee->getID(), 'name'=> $employee->getDisplayName());
        }
        return new JsonResponse($list_opts);
    }

    public function ajaxFilterEmployeeDeptAction($id)
    {
        $data = $this->getRequest()->query->all();
        $em = $this->getDoctrine()->getManager();
        $query = $data['query'];

        $employees = $em->getRepository("HrisWorkforceBundle:Employee")->createQueryBuilder('o')
           ->where('o.first_name LIKE :first_name')
           ->orWhere('o.last_name LIKE :last_name')
           ->andWhere('o.department = :dept_id')
           ->setParameter('first_name', "%".$query."%")
           ->setParameter('last_name', "%".$query."%")
           ->setParameter('dept_id', $id)
           ->getQuery()
           ->getResult();

        $list_opts = [];
        if (!empty($employees)) {
            foreach ($employees as $employee) {
                $list_opts[] = array('id'=>$employee->getID(), 'name'=> $employee->getDisplayName());
            }
        } else {
            $list_opts[] = array('id'=>'', 'name'=> 'No Result Found');
        }
        return new JsonResponse($list_opts);
    }

    public function updateAttendanceAction($id = null, $month= null, $year = null)
    {
        $report = $this->get('hris_attendance');

        if($month < 10)
        {
            $month = '0' . $month;
        }

        $date_compare = $year.'-'.$month.'-%';

        $attendance = $report->getTotalAttendance($id,$date_compare);
        $data = $report->getTotal($attendance);
        $resp = new Response(json_encode($data));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function updateNewChecklist($o,$data, $is_new)
    {
        $o->setOld();
        $this->url_append = '#tab_newchecklist';
    }

    protected function hookPostSave($employee , $is_new = false)
    {
        if($is_new)
        {
            $em = $this->getDoctrine()->getManager();
            $wm = $this->get('hris_workforce');
            //$employee->setEmployeeId($wm->generateEmployeeId($employee));
            $employee->setOld();
            $em->persist($employee);
            $wm->assignLeave($employee);
            $wm->assignBenefit($employee);
            $wm->setNewSalary($employee);
            $em->flush();
        }
    }

    public function callbackGrid($id)
    {
        $params = array(
            'id' => $id,
            'route_edit' => $this->getRouteGen()->getEdit(),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'HrisWorkforceBundle:Employee:action.html.twig',
            $params
        );
    }

    public function resendDetailsAction($id)
    {
        $this->url_append = "#tab_account";
        $wm = $this->get('hris_workforce');

        $user = $wm->resendAccountDetails($id);
        $this->sendRegistrationEmail($user);

        $this->addFlash('success', 'New account details sent successfully to registered e-mail.');

        return $this->redirect($this->generateUrl($this->getRouteGen()->getEdit(),array('id'=>$id)).$this->url_append);
    }

    public function print201Action($id)
    {
        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $twig = "HrisWorkforceBundle:Employee:print201.html.twig";

        $conf = $this->get('gist_configuration');
        $media = $this->get('gist_media');
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
            $params['company_address'] = $em->getRepository('GistContactBundle:Address')->find($conf->get('hris_com_info_company_address'));
        }
        
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
        
        //params here
        $params['employee'] = $wf->getEmployee($id);
        if ($wf->getEmployee($id)->getApplication() != null) 
        {
            $params['applicant'] = $em->getRepository('HrisRecruitmentBundle:Application')->find($wf->getEmployee($id)->getApplication());
        }
        //////////////////////////////////////////////////////////////////////////
        //FOR MEMOS
        $emp_mem = $em->getRepository('HrisMemoBundle:Memo')->findBy(
                array('employee' => $id)
            );
        $params['emp_mem'] = $emp_mem;
        $mem_contents = array();
        $index = 0;
        foreach ($emp_mem as $obj) 
        {
            $content = json_decode($obj->getContent(), true);
            if ($obj->getType() == 'Promotion')
            {
                $mem_contents[$index]['position_from']   = $content['position_from'];
                $mem_contents[$index]['position_to']     = $content['position_to'];
                $mem_contents[$index]['department_from'] = $content['department_from'];
                $mem_contents[$index]['department_to']   = $content['department_to'];
                $mem_contents[$index]['joblevel_from']   = $content['joblevel_from'];
                $mem_contents[$index]['joblevel_to']     = $content['joblevel_to'];
            }
            elseif ($obj->getType() == 'Tardiness')
            {
                $mem_contents[$index]['message']     = $content['message'];
                $mem_contents[$index]['consequence'] = $content['consequence'];
                $report                = $this->get('hris_attendance');
                $month                 = $content['month'];
                $year                  = $content['year'];
                $date_compare          = $year.'-'.$month.'-%';
                $attendance            = $report->getTotalAttendance($obj->getEmployee()->getID(),$date_compare);
                $mem_contents['dates_tardy'] = $report->getDatesTardy($attendance);
            }
            elseif ($obj->getType() == 'Regularization')
            {
                $mem_contents[$index]['basicsalary_from'] = intval(str_replace(',', '', $content['basicsalary_from']));
                $mem_contents[$index]['basicsalary_to']   = intval(str_replace(',', '', $content['basicsalary_to']));
                $mem_contents[$index]['allowance_from']   = intval(str_replace(',', '', $content['allowance_from']));
                $mem_contents[$index]['allowance_to']     = intval(str_replace(',', '', $content['allowance_to']));
            }
            elseif ($obj->getType() == 'Violation')
            {
                $mem_contents[$index]['date_of_incident']    = $content['date_of_incident'];
                $mem_contents[$index]['location']            = $content['location'];
                $mem_contents[$index]['violation_committed'] = $content['violation_committed'];
                $mem_contents[$index]['violated_policy']     = $content['violated_policy'];
                $mem_contents[$index]['agency']              = $content['agency'];
            }
            elseif ($obj->getType() == 'Disciplinary')
            {
                $mem_contents[$index]['date_of_incident']    = $content['date_of_incident'];
                $mem_contents[$index]['location']            = $content['location'];
                $mem_contents[$index]['violation_committed'] = $content['violation_committed'];
                $mem_contents[$index]['violated_policy']     = $content['violated_policy'];
                $mem_contents[$index]['date_start']          = $content['date_start'];
                $mem_contents[$index]['date_end']            = $content['date_end'];
                $mem_contents[$index]['other_actions']       = $content['other_actions'];
                $mem_contents[$index]['reason']              = $content['reason'];
                $mem_contents[$index]['date_of_exp']         = $content['date_of_exp'];
                $mem_contents[$index]['action']              = $content['action'];
                $mem_contents[$index]['other_actions']              = $content['other_actions'];

                $date_start = date_create($content['date_start']);
                $date_end = date_create($content['date_end']);
                $interval = date_diff($date_start, $date_end);
                $mem_contents['no_of_days'] = $interval->format('%a');
            }
            $index++;
        }
        // echo "<pre>";
        // var_dump($mem_contents);
        // echo "</pre>";
        // exit();
        $params['mem_contents'] = $mem_contents;
        ///////////////////////////////////////////////////////////////////////////
        //FOR ATTENDANCE
        $emp_obj = $wf->getEmployee($id);
        $dfrom = $emp_obj->getDateHired();
        $date = new DateTime();
        $date_from = new DateTime($dfrom->format('Ymt'));
        $date_to = new DateTime($date->format('Ymt'));
        $query = $em->createQueryBuilder();
        $query                  
            ->from('HrisWorkforceBundle:Attendance', 'o')
            ->where('o.date >= :date_from and o.date <= :date_to and o.employee = :id')
            ->setParameter('date_from', $date_from)
            ->setParameter('date_to', $date_to)
            ->setParameter('id', $id);
                                
        $data = $query->select('o')
            ->getQuery()
            ->getResult();   
        $params['emp'] = $em->getRepository('HrisWorkforceBundle:Employee')->find($id);
        $params['dept'] = null;
        $params['date_from_display'] = $date_from;
        $params['date_to_display'] = $date_to;
        $params['all'] = $data;
        ///////////////////////////////////////////////////////////////////////////

        $pdf = $this->get('gist_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }

    protected function filterGrid(){
        $grid = $this->get('gist_grid');

        $fg = $grid->newFilterGroup();

        $qry = "o.enabled != false";
        $qry = "o.employment_status != 'Resigned'";

        return $fg->where($qry); 
    }

    public function viewEmailTemplateAction() {
        $twig = 'HrisWorkforceBundle:Employee:registration_email.html.twig';

        return $this->render($twig);
    }
}

