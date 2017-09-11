<?php

namespace Hris\ProfileBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;

use Hris\WorkforceBundle\Entity\Employee;
use Hris\WorkforceBundle\Entity\EmployeeChecklist;
use Hris\WorkforceBundle\Entity\EmployeeBenefits;
use Gist\ContactBundle\Entity\Address;
use Gist\UserBundle\Entity\User;
use Hris\WorkforceBundle\Entity\IncidentReport;

use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\ContactBundle\Template\Controller\HasPhones;

use DateTime;

class EmployeeController extends CrudController
{
    use TrackCreate;
    use HasPhones;

	public function __construct()
	{
		$this->route_prefix = 'hris_profile_employee';
		$this->title = 'Employee Profile';

		$this->list_title = 'Employee Profile';
        $this->list_type = 'dynamic';
        $this->submit_redirect = false;
        $this->repo = 'HrisWorkforceBundle:Employee';
	}

	public function indexAction()
	{
		$emp = $this->get('hris_workforce');
		$this->title = 'Employee Profile';
		$params = $this->getViewParams('view', 'hris_profile_employee_index');
		$this->padFormPhoneType($params);

		if ($this->getUser()->getEmployee() != null or $this->getUser()->getEmployee() != '') {
			$params['object'] = $emp->getEmployee($this->getUser()->getEmployee());
            $this->padFormParams($params, $params['object']);
		}

		return $this->render('HrisProfileBundle:Employee:index.html.twig', $params);
	}

    public function editInfoAction()
    {
        $this->hookPreAction();

        $id = $this->getUser()->getEmployee()->getId();

        try
        {
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

            switch($data['employee_form']){
                case 'acct_profile':
                    $this->addFlash('success', $this->title . ' ' . $this->getObjectLabel($object) . ' edited successfully.');
                    break;
                case 'acct_user':
                    $this->addFlash('success', $this->title . ' ' . $this->getObjectLabel($object) . ' edited successfully.');
                    break;
                case 'incident_create':
                    $this->addFlash('success','Succesfully created Incident Report and submitted to HR. ');
                    break;;
            }

            return $this->redirect($this->generateUrl('hris_profile_employee_index').$this->url_append);
        }
        catch (ValidationException $e)
        {
            $this->addFlash('error', $e->getMessage());
            return $this->editError($object, $id);
        }
        catch (DBALException $e)
        {
            $this->addFlash('error', 'Database error encountered. Possible duplicate.');
            error_log($e->getMessage());
            
            return $this->editError($object);
        }
    }

    protected function editError($obj, $id)
    {
        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);

        $this->padFormParams($params, $obj);

        return $this->render('HrisProfileBundle:Employee:index.html.twig', $params);
    }

	protected function getObjectLabel($object) {
        
    }

    protected function newBaseClass() {
        
    }

    protected function update($o, $data, $is_new = false)
    {
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();

        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        switch($data['employee_form']){
            case 'acct_profile':
                $this->updateProfile($o,$data, $is_new);
                break;
            case 'acct_user':
                $this->updateAccount($o,$data, $is_new);
                break;
            case 'incident_create':
                $obj = new IncidentReport();
                $this->updateIncident($obj,$data, $is_new);
                break;;
        }

        $this->updateTrackCreate($o, $data, $is_new);

    }

    protected function updateProfile($o, $data, $is_new)
    {
        $this->url_append = "#acct_profile";
        $profile = $o->getProfile();
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');
        $media = $this->get('gist_media');

        $profile->setSss($data['sss']);
        $profile->setTin($data['tin']);
        $profile->setPagibig($data['pagibig']);
        $profile->setPhilhealth($data['philhealth']);
        $profile->setBirthday(new DateTime($data['birthday']));
        if($data['picture']!=0 && $data['picture'] != ""){
            $profile->setUpload($media->getUpload($data['picture']));
        }

        $profile->setAddress($this->updateAddress($o,$data,$is_new));
        $this->updateHasPhones($profile,$data,$is_new);
        $em->persist($profile);
    }

    protected function updateAccount($o, $data, $is_new)
    {
        $this->url_append = "#acct_user";
        $em = $this->getDoctrine()->getManager();
        $user = $o->getUser();
        $um = $this->container->get('fos_user.user_manager');

        //Update password if not empty
        if(($data['pass1']== $data['pass2']) && $data['pass1'] != ""){
            
            $user->setPlainPassword($data['pass1']);
            $um->updatePassword($user);
        }
        $user->setEmailNotify($data['email_notify']);
        $user->setEnabled(true);
        $em->persist($user);
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

    protected function updateIncident($o, $data, $is_new = true)
    {
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();
        $this->url_append = "#tab_incident";
        $em = $this->getDoctrine()->getManager();

        // set dates
        $o->setDateHappened(new DateTime($data['doi']))
            ->setDateFiled(new DateTime());

        // set notes
        $o->setNotes($data['desc'])
            ->setConcerns($data['concerns']);

        // set info
        $emp = $em->getRepository('HrisWorkforceBundle:Employee')->find($data['emp_id']);
        $o->setEmployee($emp);

        $dept = $em->getRepository('HrisAdminBundle:Department')->find($data['dept']);
        $o->setDepartment($dept);

        $loc = $em->getRepository('HrisAdminBundle:Location')->find($data['loc']);
        $o->setLocation($loc);

        $o->setReporter($this->getUser());

        $o->setProducts($data['prod']);

        $this->updateTrackCreate($o, $data, true);

        $em->persist($o);
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
        $config = $this  ->get('gist_configuration');
        $comp_name = $config->get('hris_com_info_company_name');
        $acronym = "";

        if ($comp_name != '' or $comp_name != NULL) {
            $words = explode(" ", $config->get('hris_com_info_company_name'));

            foreach ($words as $w) {
              $acronym .= $w[0];
            }
        }

        $this->padFormPhoneType($params);

        $emp_ben = $em->getRepository('HrisWorkforceBundle:EmployeeBenefits')->findBy(
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

        $emp_leave = $em->getRepository('HrisWorkforceBundle:EmployeeLeaves')->findBy(
                array('employee' => $object->getID())
            );
        $downloadable = $em->getRepository('HrisAdminBundle:Downloadables')->findAll();

        $params['emp_leave'] = $emp_leave;
        $params['emp_ben'] = $emp_ben;
        $params['appr'] = $appraisal;
        $params['appr_result'] = $this->getAppraisalResults($appraisal);
        $params['evals'] = $evals;
        $params['downloadable'] = $downloadable;
        $params['company_name_abv'] = $acronym;
        $params['sendmail_opts'] = array(1 => 'Yes', 0=>'No');
     
        $expense_opts = array(
            'Meal' => 'Meal Allowance',
            'Travel' => 'Travel Allowance',
            'Transportation' => 'Transportation Expense',
            'Others' => 'Others'
            );
        $params['expense_opts'] = $expense_opts;

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
                    $state_opts[$object->getProfile()->getAddress()->getState()->getID()] = $object->getProfile()->getAddress()->getState()->getName();
                    $params['state_opts'] = $state_opts;
                }
                else
                {
                    $params['state_opts'] = '0';
                }

                $city_opts = array();
                if ($object->getProfile()->getAddress()->getCity() != null) 
                {
                    $city_opts[$object->getProfile()->getAddress()->getCity()->getID()] = $object->getProfile()->getAddress()->getCity()->getName();
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

        $this->incidentFormParams($params);

        return $params;
    }

    protected function incidentFormParams(&$params)
    {
        $em = $this->getDoctrine()->getManager();

        $object = new IncidentReport();

        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $action = array(
                'Violation' => 'Violation',
                'Disciplinary' => 'Disciplinary Action'
            );

        $departments = $em->getRepository('HrisAdminBundle:Department')->findAll();
        foreach ($departments as $dept) {
            $department[$dept->getID()] = $dept->getName();
        }
        $params['dept_opts'] = $department;

        $locations = $em->getRepository('HrisAdminBundle:Location')->findAll();
        foreach ($locations as $loc) {
            $location[$loc->getID()] = $loc->getName();
        }
        $params['loc_opts'] = $location;
        $params['incident'] = $object;

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

    public function ajaxEmpWorkdaysAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $emp = $em->getRepository("HrisWorkforceBundle:Employee")->find($id);

        $workday_end = $emp->getSchedule()->getDayEnd();

        return new JsonResponse($workday_end);
    }

    public function updateAttendanceAction($id = null, $date_from = null, $date_to = null)
    {
        $report = $this->get('hris_attendance');

        $date = new DateTime();

        $date_from = $date_from=='null'? $date->format('Ym01'):new DateTime($date_from);
        $date_to = $date_to=='null'? $date->format('Ymt'):new DateTime($date_to);

        $attendance = $report->getTotalAttendance($id,$date_from,$date_to);
        $data = $report->getTotal($attendance);
        $resp = new Response(json_encode($data));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
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
            $wm->assignBenefit($employee);
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
}