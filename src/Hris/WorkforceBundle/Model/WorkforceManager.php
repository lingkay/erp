<?php

namespace Hris\WorkforceBundle\Model;

use Doctrine\ORM\EntityManager;
use Gist\UserBundle\Entity\User;
use Hris\AdminBundle\Entity\Benefit;
use Hris\AdminBundle\Model\SettingsManager;
use Hris\WorkforceBundle\Entity\Employee;
use Hris\WorkforceBundle\Entity\Profile;
use Hris\WorkforceBundle\Entity\Appraisal;
use Hris\WorkforceBundle\Entity\Evaluator;
use Hris\WorkforceBundle\Entity\EmployeeChecklist;
use Hris\WorkforceBundle\Entity\EmployeeBenefits;
use Hris\WorkforceBundle\Entity\EmployeeLeaves;
use Hris\WorkforceBundle\Entity\SalaryHistory;
use Gist\NotificationBundle\Entity\Notification;
use Gist\NotificationBundle\Model\NotificationEvent;
use DateTime;

class WorkforceManager
{
    protected $em;
    protected $container;
    protected $user;

    public function __construct(EntityManager $em,$container = null, $securityContext = null)
    {
        $this->em = $em;
        $this->container = $container;
        $this->user = $securityContext->getToken() != NULL ? $securityContext->getToken()->getUser() : '';
    }

    public function getEmployee($id)
    {
        return $this->em->getRepository('HrisWorkforceBundle:Employee')->find($id);
    }

    public function getEmployees($filter)
    {
        $employees = $this->em
            ->getRepository('HrisWorkforceBundle:Employee')
            ->findBy(
                $filter
            );

        return $employees;
    }

    public function generateProfile(Employee $employee)
    {
        $profile = new Profile();
        $profile->setEmployee($employee);
        $this->em->persist($profile);
        $this->em->flush();
    }

    public function generateUser(Employee $employee)
    {
        $user = new User();
        $conf = $this->container->get('gist_configuration');
        $setting = $this->container->get('hris_settings');
        $user->setName($employee->getFirstName().' '.$employee->getLastName());
        $user->setUsername($this->generateUsername($employee));
        $password = $this->generatePassword();
        $user->setPlainPassword($password);
        if($employee->getEmail() != null && $employee->getEmail()!=''){
            $user->setEmail($employee->getEmail());
        };

        $user->setEnabled(true);
        if($conf->get('hris_hr_department') != NULL || $conf->get('hris_hr_department') != '')
        {
            $hr = $setting->getDepartment($conf->get('hris_hr_department'));    
        }
        else
        {
            $hr = '';
        } 
        $group = [];
        if($employee->getDepartment() === $hr)
        {
            $group[] = $this->em->getRepository('GistUserBundle:Group')->findOneBy(array("name" => "hr_admin"));
        }

        $group[] = $this->em->getRepository('GistUserBundle:Group')->findOneBy(array("name" => "employee"));

        foreach ($group as $grp) {
            $user->addGroup($grp);
        }


        $this->em->persist($user);
        $this->em->persist($employee);
        $this->em->flush();

        $user->setEmployee($employee);
        $this->em->persist($user);
        $this->em->flush();

        $user->setPlainPassword($password);
        return $user;
    }

    function cleanString($text) 
    {
        // 1) convert á ô => a o
        $text = preg_replace("/[áàâãªä]/u","a",$text);
        $text = preg_replace("/[ÁÀÂÃÄ]/u","A",$text);
        $text = preg_replace("/[ÍÌÎÏ]/u","I",$text);
        $text = preg_replace("/[íìîï]/u","i",$text);
        $text = preg_replace("/[éèêë]/u","e",$text);
        $text = preg_replace("/[ÉÈÊË]/u","E",$text);
        $text = preg_replace("/[óòôõºö]/u","o",$text);
        $text = preg_replace("/[ÓÒÔÕÖ]/u","O",$text);
        $text = preg_replace("/[úùûü]/u","u",$text);
        $text = preg_replace("/[ÚÙÛÜ]/u","U",$text);
        $text = preg_replace("/[’‘‹›‚]/u","'",$text);
        $text = preg_replace("/[“”«»„]/u",'"',$text);
        $text = str_replace("–","-",$text);
        $text = str_replace(" "," ",$text);
        $text = str_replace("ç","c",$text);
        $text = str_replace("Ç","C",$text);
        $text = str_replace("ñ","n",$text);
        $text = str_replace("Ñ","N",$text);
     
        //2) Translation CP1252. &ndash; => -
        $trans = get_html_translation_table(HTML_ENTITIES); 
        $trans[chr(130)] = '&sbquo;';    // Single Low-9 Quotation Mark 
        $trans[chr(131)] = '&fnof;';    // Latin Small Letter F With Hook 
        $trans[chr(132)] = '&bdquo;';    // Double Low-9 Quotation Mark 
        $trans[chr(133)] = '&hellip;';    // Horizontal Ellipsis 
        $trans[chr(134)] = '&dagger;';    // Dagger 
        $trans[chr(135)] = '&Dagger;';    // Double Dagger 
        $trans[chr(136)] = '&circ;';    // Modifier Letter Circumflex Accent 
        $trans[chr(137)] = '&permil;';    // Per Mille Sign 
        $trans[chr(138)] = '&Scaron;';    // Latin Capital Letter S With Caron 
        $trans[chr(139)] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark 
        $trans[chr(140)] = '&OElig;';    // Latin Capital Ligature OE 
        $trans[chr(145)] = '&lsquo;';    // Left Single Quotation Mark 
        $trans[chr(146)] = '&rsquo;';    // Right Single Quotation Mark 
        $trans[chr(147)] = '&ldquo;';    // Left Double Quotation Mark 
        $trans[chr(148)] = '&rdquo;';    // Right Double Quotation Mark 
        $trans[chr(149)] = '&bull;';    // Bullet 
        $trans[chr(150)] = '&ndash;';    // En Dash 
        $trans[chr(151)] = '&mdash;';    // Em Dash 
        $trans[chr(152)] = '&tilde;';    // Small Tilde 
        $trans[chr(153)] = '&trade;';    // Trade Mark Sign 
        $trans[chr(154)] = '&scaron;';    // Latin Small Letter S With Caron 
        $trans[chr(155)] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark 
        $trans[chr(156)] = '&oelig;';    // Latin Small Ligature OE 
        $trans[chr(159)] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis 
        $trans['euro'] = '&euro;';    // euro currency symbol 
        ksort($trans); 
         
        foreach ($trans as $k => $v) {
            $text = str_replace($v, $k, $text);
        }
     
        // 3) remove <p>, <br/> ...
        $text = strip_tags($text); 
         
        // 4) &amp; => & &quot; => '
        $text = html_entity_decode($text);
         
        // 5) remove Windows-1252 symbols like "TradeMark", "Euro"...
        $text = preg_replace('/[^(\x20-\x7F)]*/','', $text); 
         
        $targets=array('\r\n','\n','\r','\t');
        $results=array(" "," "," ","");
        $text = str_replace($targets,$results,$text);
     
        //XML compatible
        /*
        $text = str_replace("&", "and", $text);
        $text = str_replace("<", ".", $text);
        $text = str_replace(">", ".", $text);
        $text = str_replace("\\", "-", $text);
        $text = str_replace("/", "-", $text);
        */
         
        return ($text);
    } 

    protected function generateUsername(Employee $employee)
    {
        $fname = preg_replace('/^a-zA-Z/', ' ',$employee->getFirstName());
        $name = explode(" ", strtolower(trim($fname)));
        $first = "";
        foreach ($name as $n) {
          $first .= $n[0];
        }
        $middle = strtolower(substr(preg_replace('/^a-zA-Z/', '',$employee->getMiddleName()), 0,1));
        $last = strtolower(preg_replace('/\s*/', '', $this->cleanString($employee->getLastName())));
        $last = preg_replace('/[.,]/', '', $last);
        $generated = $first.$middle.$last;

        $bool = $this->checkUsername($generated);

        if ($bool)
        {
            $counter = 0;
            $check = true;
            while ($check == true)
            {
                $counter++;
                $new_generated = $generated.$counter;
                $check = $this->checkUsername($new_generated);
            }
            return $new_generated;
        }
        else
        {
            return $generated;
        }

    }

    protected function checkUsername($username)
    {
        $employees = $this->em->getRepository("GistUserBundle:User")->findByUsername($username);
        if (count($employees) > 0) 
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    protected function generatePassword()
    {
        return substr(uniqid(),0,10);
    }

    public function checkAppraisals()
    {
        $employees = $this->em->getRepository("HrisWorkforceBundle:Employee")->findByEnabled(true);
        $date = new Datetime();
        $this->sendToUsers($notification, $users);
    }

    public function checkForEmployeeAppraisal()
    {
        $em = $this->em;
        $today = new DateTime();
        $date_today = $today;
        $employees = $em->getRepository('HrisWorkforceBundle:Employee')->findAll();

        foreach ($employees as $employee) 
        {
            $config = $this->container->get('gist_configuration');
            $settings = $this->container->get('hris_settings');
            $hr_dept = $config->get('hris_hr_department');

            $date_hired = $employee->getDateHired();
            $difference = date_diff($date_hired, $date_today);
            $months_hired = $difference->format('%m'); 
            if ($months_hired == 5) 
            {
                if ($hr_dept == null){}
                else
                {
                    $hr = $settings->getDepartment($hr_dept);
                    $hr = $hr->getDeptHead();

                    if ($hr == null){}
                    else
                    {
                        $event = new NotificationEvent();
                        $notif_body = array(
                                'link'=>'/notifications',
                                'type'=> Notification::TYPE_UPDATE);
                        $notif_body['receipient']   = $hr;
                        $notif_body['message']      = $employee->getDisplayName().' has reached 5 months of being employed.';
                        $notif_body['source']       = 'Create appraisal';
                        $event->notify($notif_body);
                        $dispatcher = $this->container->get('event_dispatcher');
                        $dispatcher->dispatch('notification.event', $event);
                    }
                }
            }
            else
            {

            }     
        }
    }

    public function generateEmployeeId(Employee $employee)
    {
        return str_pad($employee->getID(), 5, '0', STR_PAD_LEFT);;
    }

    public function copyApplicant($applicant,$user)
    {
        $settings = $this->container->get('hris_settings');
        $payroll = $this->container->get('hris_payroll');
        $employee = new Employee();

        $employee->setUserCreate($user);
        $employee->setFirstName($applicant->getFirstName());
        $employee->setMiddleName($applicant->getMiddleName());
        $employee->setLastName($applicant->getLastName());
        $employee->setGender($applicant->getProfile()->getGender());
        // $o->setEmployeeId($data['employee_id']);
        // $o->setEmail($data['email']);

        $position = $settings->getJobTitle($applicant->getJobOffer()['position']);

        $employee->setDepartment($position->getDepartment());
        $employee->setJobTitle($position);
        $employee->setApplication($applicant);
        $employee->setJobLevel($settings->getJobLevel($applicant->getJobOffer()['level']));
        $employee->setLocation($settings->getLocation($applicant->getJobOffer()['location']));
        $employee->setEmploymentStatus($applicant->getJobOffer()['employment_status']);
        $employee->setSchedule($settings->getSchedule($applicant->getJobOffer()['schedule']));
        $employee->setPayRate($applicant->getJobOffer()['salary']);
        $employee->setPaySchedule($payroll->getPayType($applicant->getJobOffer()['pay_schedule']));
        $employee->setPayPeriod($payroll->getPayType($applicant->getJobOffer()['pay_type']));
        // $employee->setSupervisor($wf->getEmployee($data['supervisor_id']));

        $employee->setDateHired(new DateTime());

        $profile = $this->generateProfile($employee);
        $profile->setUpload($applicant->getUpload());
        $employee->setProfile($profile);
        $user = $this->generateUser($employee);
        $employee->setUser($user);

        $this->em->persist($employee);
        $this->em->flush();

        //$employee->setEmployeeId($this->generateEmployeeId($employee));
        $this->updateEmployeeProfile($employee, $applicant);
        $this->updatePreEmploymentChecklist($employee, $applicant);
        $this->assignBenefit($employee);

        $this->em->flush();
        $this->setNewSalary($employee);
    }

    protected function updateEmployeeProfile($employee, $applicant)
    {
        $employee_profile = $this->em->getRepository('HrisWorkforceBundle:Profile')->findOneBy(array('employee' => $employee->getID()));
        $applicant_profile = $applicant->getProfile();
        $applicant_information = $applicant->getInformation();

        $sss = null;
        $tin = null;
        $pagibig = null;
        $philhealth = null;
        $birthday = new Datetime();
        $address = null;
        
        if(count($applicant_profile) >= 1)
        {
            $birthday = new DateTime($applicant_profile->getBirthDate()->format('m/d/Y'));
            $address = $applicant_profile->getPermanentAddress(); 
        }

        if(count($applicant_information) >= 1)
        {
            $sss = $applicant_information->getSSSNumber();
            $tin = $applicant_information->getTinNumber();
            $pagibig = $applicant_information->getPagIbigNumber();
            $philhealth = $applicant_information->getPhilHealthNumber();
        }

        $employee_profile->setSss($sss);
        $employee_profile->setTin($tin);
        $employee_profile->setPagibig($pagibig);
        $employee_profile->setPhilhealth($philhealth);
        $employee_profile->setBirthday($birthday);
        $employee_profile->setAddress($address);
    }

    protected function updatePreEmploymentChecklist($employee, $applicant)
    {
        $settings = $this->container->get('hris_settings');
        $employee_profile = $this->em->getRepository('HrisWorkforceBundle:Profile')->findOneBy(array('employee' => $employee->getID()));

        foreach ($employee_profile->getChecklist() as $checklist ) {
            $this->em->remove($checklist);
        }
        $employee_profile->clearChecklist();
        $this->em->flush();

        foreach($applicant->getChecklist() as $id => $checklist) {
            $ec = new EmployeeChecklist($employee_profile,$checklist->getChecklist());
            $ec ->setStatus($checklist->getStatus())
                ->setNotes($checklist->getNotes())
                ->setDateReceived($checklist->getDateReceived());

            $employee_profile->addChecklist($ec);
        }

    }

    public function resendAccountDetails($id)
    {
        $emp = $this->getEmployee($id);
        $um = $this->container->get('fos_user.user_manager');

        $user = $this->em->getRepository('GistUserBundle:User')
            ->find($emp->getUser()->getId());

        $password = $this->generatePassword();
        $user->setPlainPassword($password);
        $um->updatePassword($user);

        $this->em->persist($user);
        $this->em->flush();

        $user->setPlainPassword($password);
        return $user;
    }

    public function assignBenefit($employee) 
    {
        $benefits = $this->em->getRepository('HrisAdminBundle:Benefit')->findAll();
        $leaves = $this->em->getRepository('HrisAdminBundle:Leave\LeaveType')->findAll();

        foreach ($benefits as $benefit) {
            if(in_array($employee->getEmploymentStatus(), $benefit->getEmpStatus()) &&
                in_array($employee->getGender(), $benefit->getGender()) && 
                in_array($employee->getDepartment()->getID(), $benefit->getDepartment()) ) {

                $employee_benefit = new EmployeeBenefits();

                $employee_benefit->setEmployee($employee);
                $employee_benefit->setBenefit($benefit);

                $this->em->persist($employee_benefit);
            }
        }

        foreach ($leaves as $leave) {
            if(in_array($employee->getEmploymentStatus(), $leave->getEmpStatus()) &&
                in_array($employee->getGender(), $leave->getGender()) ) {

                $employee_benefit = new EmployeeBenefits();

                $employee_benefit->setEmployee($employee);
                $employee_benefit->setLeave($leave);

                $this->em->persist($employee_benefit);
            }
        }
    }

    public function assignLeave($employee)
    {
        $leaves = $this->em->getRepository('HrisAdminBundle:Leave\LeaveType')->findAll();

        foreach ($leaves as $leave) {
            if(in_array($employee->getEmploymentStatus(), $leave->getEmpStatus()) &&
                in_array($employee->getGender(), $leave->getGender()) ) {

                $emp_leave = new EmployeeLeaves();

                $emp_leave->setEmployee($employee);
                $emp_leave->setLeaveType($leave);
                $emp_leave->setAvailLeaves($leave->getLeaveCount());
                $emp_leave->setLeaveYear(date('Y'));

                $this->em->persist($emp_leave);
            }
        }
    }

    public function hasEmployeeBenefit($employee, $benefitName)
    {
        $benefit = $this->em->getRepository('HrisAdminBundle:Benefit')->findOneByName($benefitName);

        if($benefit == null){
            return false;
        }
        
        $benefits = $this->em->getRepository('HrisWorkforceBundle:EmployeeBenefits')
            ->findOneBy(array(
                'employee' => $employee,
                'benefit' => $benefit)
            );

        if($benefits != null){
            return true;
        }else {
            return false;
        }
    }

    public function getSubordinates($employee)
    {
        // insert function here
    }

    public function setNewSalary($employee)
    {
        $sh = new SalaryHistory();
        $sh->setEmployee($employee)
            ->setPay($employee->getPayRate())
            ->setPayPeriod($employee->getPayPeriod())
            ->setUserCreate($this->user);
        $this->em->persist($sh);
        $this->em->flush();
    }

    public function checkSalaryChange($employee)
    {
        $sh = $this->em->getRepository('HrisWorkforceBundle:SalaryHistory')->getLatestPayByEmployee($employee);
        if($sh== null || $sh->getPay() != $employee->getPayRate()){
            $this->setNewSalary($employee);
        }
    }

    public function notifyForApproval($emp_obj, $route, $message = 'Your approval is required for this memo.')
    {
        $event = new NotificationEvent();
        $notif_body = array(
                'link'=> $route,
                'type'=> Notification::TYPE_ALERT);
        $notif_body['receipient']   = $emp_obj;
        $notif_body['message']      = $message;
        $notif_body['source']       = 'Memo received for review';
        $event->notify($notif_body);
        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->dispatch('notification.event', $event);
    }
}