<?php

namespace Hris\BiometricsBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Catalyst\ValidationException;
use Hris\WorkforceBundle\Entity\Attendance;
use Hris\BiometricsBundle\Entity\AuthToken;
use Hris\BiometricsBundle\Entity\BiometricsAttendance;
use Catalyst\NotificationBundle\Model\NotificationEvent;
use Catalyst\NotificationBundle\Entity\Notification;
use Catalyst\CoreBundle\Template\Controller\TrackCreate;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use DateInterval;

class BiometricsController extends CrudController
{
    use TrackCreate;

    public function __construct()
    {
        $this->route_prefix = 'hris_biometrics';
        $this->title = 'Biometrics Access Settings';

        $this->list_title = 'Biometrics Access Settings';
        $this->list_type = 'dynamic';
    }


    protected function getObjectLabel($obj)
    {
        if ($obj == null)
        {
            return '';
        }
        return $obj->getID();
    }

    protected function newBaseClass()
    {
        return new Attendance();
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');
        
        $params = $this->getViewParams('List', 'hris_biometrics_homepage');
        $conf = $this->get('catalyst_configuration');
        $params['username'] = $conf->get('hris_biometrics_username');
        $params['password'] = $conf->get('hris_biometrics_password');
        $cm = $this->get('catalyst_contact');
        $em = $this->getDoctrine()->getManager();
        $media = $this->get('catalyst_media');

        return $this->render('HrisBiometricsBundle:Biometrics:index.html.twig', $params);
    }

    public function indexSubmitAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();
        $conf = $this->get('catalyst_configuration');
        $media = $this->get('catalyst_media');

        $conf->set('hris_biometrics_username', $data['username']);
        $conf->set('hris_biometrics_password', $data['password']);
        $em->flush();   
        $this->addFlash('success', $this->title . ' updated successfully.');

        return $this->redirect($this->generateUrl($this->getRouteGen()->getList())); 
    }


    public function authTransmissionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        $conf = $this->get('catalyst_configuration');
        $conf_username = $conf->get('hris_biometrics_username');
        $conf_password = $conf->get('hris_biometrics_password');

        if ($username == $conf_username && $password == $conf_password) 
        {
            $token_obj = $em->getRepository('HrisBiometricsBundle:AuthToken')->findOneBy(array('date' => new DateTime()));
            if ($token_obj != null) 
            {
                $resp = new Response($token_obj->getToken());
            }
            else
            {
                $new_token = bin2hex(openssl_random_pseudo_bytes(16));
                $auth = new AuthToken();
                $auth->setDate(new DateTime());
                $auth->setToken($new_token);
                $em->persist($auth);
                $em->flush();
                $resp = new Response($new_token);
            }
        }
        else
        {
            $resp = new Response('invalid');
        }
        return $resp;
    }

    public function receiveBiometricsDataAction(Request $request)
    {
        //SAVE ALL DATA TO biometrics_attendance
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();


        //CHECK IF TOKEN IS VALID
        $token_obj = $em->getRepository('HrisBiometricsBundle:AuthToken')->findOneBy(array('token' => $data['TOKEN'], 'date' => new DateTime() ));

        if ($token_obj != null) 
        {
            $employee_obj = $em->getRepository('HrisWorkforceBundle:Employee')->findOneBy(array('employee_code' => $data['ID'] ));
            $user_obj = $em->getRepository('CatalystUserBundle:User')->findOneBy(array('username' => "admin" ));
            if ($employee_obj != null) 
            {
                $bio_att = new BiometricsAttendance();
                $bio_att->setEmployee($employee_obj);
                $bio_att->setCheckType($data['CHECKTYPE']);
                $bio_att->setCheckTime(new DateTime($data['CHECKTIME']));
                $bio_att->setDate(new DateTime($data['DATE']));
                $em->persist($bio_att);
                $em->flush();

                $resp = new Response('emp_found');
            }
            else
            {
                $resp = new Response('emp_not_found');
            }
        }
        else
        {
            $resp = new Response('auth_error');
        }


        return $resp;
    }

    public function pairAttendanceRecordsAction(Request $request)
    {
        $data_post = $this->getRequest()->request->all();
        $em = $this->getDoctrine()->getManager();
        $date = $request->request->get('DATE');
        $date = new DateTime($date);
        $user_obj = $em->getRepository('CatalystUserBundle:User')->findOneBy(array('username' => "admin" ));
        $data = array();
        $data['DATE'] = $data_post['DATE'];
        $employee_ids = $this->getEmployeeIDs($date);
        foreach ($employee_ids as $emp_id)
        {
            $employee_obj = $em->getRepository('HrisWorkforceBundle:Employee')->findOneBy(array('id' => $emp_id ));
            if ($employee_obj != null) 
            {
                //GET FIRST ENTRY IF O 
                $first_entry = $em->getRepository('HrisBiometricsBundle:BiometricsAttendance')->findOneBy(array('date' => $date, 'employee' => $emp_id), array('checktime' => 'ASC'));
                if (count($first_entry) > 0 && $first_entry->getCheckType() == "O") 
                {
                    $data['OPERATION'] = "backtrack";
                    $data['TIME_OUT'] = $first_entry->getCheckTime();
                    $resp = new Response($this->backTrackAttendance($employee_obj, $data));
                }
                else
                {
                    //FIRST ENTRY FOR THE DAY IS NOT OUT
                }

                //QUERY FOR IN & OUT (LILO)
                $time_in_l1 = $em->getRepository('HrisBiometricsBundle:BiometricsAttendance')->findOneBy(array('date' => $date, 'checktype' => 'I', 'employee' => $emp_id), array('checktime' => 'DESC'));
                if (count($time_in_l1) > 0)
                {
                    $sTimeIn = $time_in_l1->getCheckTime();
                    //QUERY FOR TIME OUT (LILO)
                    $time_out_l1 = $em->getRepository('HrisBiometricsBundle:BiometricsAttendance')->findOneBy(array('date' => $date, 'checktype' => 'O', 'employee' => $emp_id), array('checktime' => 'DESC'));
                    if (count($time_out_l1) > 0) 
                    {
                        $sTimeOut = $time_out_l1->getCheckTime();
                        $cmpTimeIn = new DateTime($time_in_l1->getCheckTime());
                        $cmpTimeOut = new DateTime($time_out_l1->getCheckTime());
                        $data['TIME_IN'] = $time_in_l1->getCheckTime().'';
                        $data['TIME_OUT'] = $time_out_l1->getCheckTime().'';
                        if ($cmpTimeOut > $cmpTimeIn)
                        {
                            $resp = new Response($this->insertAttendanceEntry('normal', $data_post['DATE'], $sTimeIn, $user_obj, $employee_obj, $sTimeOut));
                        }
                        else
                        {
                            //SEARCH FOR FIRST TIME-IN
                            $time_in_x = $em->getRepository('HrisBiometricsBundle:BiometricsAttendance')->findOneBy(array('date' => $date, 'checktype' => 'I', 'employee' => $emp_id), array('checktime' => 'ASC'));    
                            $xTimeIn = $time_in_x->getCheckTime();
                            $xCmpTimeIn = new DateTime($time_in_x->getCheckTime());
                            if ($cmpTimeIn != $xCmpTimeIn) 
                            {
                                //TIME OUT FOUND IS EARLIER .NO OUT MODE. SET LAST CHECKIN AS CHECKIN TIME
                                $resp = new Response($this->insertAttendanceEntry('normal', $data_post['DATE'], $xTimeIn, $user_obj, $employee_obj, $sTimeOut));
                            }
                            else
                            {
                                //TIME OUT FOUND IS EARLIER .NO OUT MODE. SET LAST CHECKIN AS CHECKIN TIME
                                $resp = new Response($this->insertAttendanceEntry('no_out', $data_post['DATE'], $sTimeIn, $user_obj, $employee_obj));
                            }
                        }
                    }
                    else
                    {                                                                                   
                        //NO CHECKOUT FOUND, QUERY FOR POSSIBLE OTHER TIME IN (FILO)
                        $time_in_l2 = $em->getRepository('HrisBiometricsBundle:BiometricsAttendance')->findOneBy(array('date' => $date, 'checktype' => 'I', 'employee' => $emp_id), array('checktime' => 'ASC'));
                        if (count($time_in_l2) > 0)
                        {
                            $sTimeIn2 = $time_in_l2->getCheckTime();
                            //QUERY FOR TIME IN TO BE SET AS OUT
                            $time_out_l2 = $em->getRepository('HrisBiometricsBundle:BiometricsAttendance')->findOneBy(array('date' => $date, 'checktype' => 'I', 'employee' => $emp_id), array('checktime' => 'DESC'));
                            if (count($time_out_l2) > 0) 
                            {
                                $sTimeOut2 = $time_out_l2->getCheckTime();
                                $cmpTimeIn2 = new DateTime($time_in_l2->getCheckTime());
                                $cmpTimeOut2 = new DateTime($time_out_l2->getCheckTime());
                                if ($cmpTimeOut2 > $cmpTimeIn2)
                                {
                                    //$resp = new Response('p_insert_normal');
                                    $resp = new Response($this->insertAttendanceEntry('normal', $data_post['DATE'], $sTimeIn2, $user_obj, $employee_obj, $sTimeOut2));
                                }
                                else
                                {
                                    $resp = new Response($this->insertAttendanceEntry('no_out', $data_post['DATE'], $sTimeIn2, $user_obj, $employee_obj));
                                }
                            }
                            else
                            {
                                $resp = new Response($this->insertAttendanceEntry('no_out', $data_post['DATE'], $sTimeIn2, $user_obj, $employee_obj));
                            }
                        }
                        else
                        {

                        }
                    }
                }
                else
                {
                    //NO TIME IN FOUND
                }
            }
            else
            {
                //EMPLOYEE NOT FOUND.
                $resp = new Response('p_error_09');
            }
        }
        return $resp;
    }


    private function insertAttendanceEntry($mode, $date, $time_in, $user_obj, $employee_obj, $time_out = null)
    {
        $em = $this->getDoctrine()->getManager();
        $data = array();
        $data['OPERATION'] = "no_out";
        $data['DATE'] = $date;
        $resp = '';
        try
        {
            $att = new Attendance();
            $att->setDate(new DateTime($date));
            $att->setEmployee($employee_obj);
            $att->setTimeIn(new DateTime($time_in));
            if ($mode == 'normal') 
            {
                $att->setTimeOut(new DateTime($time_out));
            }
            $att->setUserCreate($user_obj);
            if ($mode == 'no_out') 
            {
                $att->setStatus(Attendance::STATUS_PRESENT);
            }
            if ($mode == 'normal') 
            {
                $data['TIME_IN'] = $time_in;
                $data['TIME_OUT'] = $time_out;
                $late = $this->getValues('late', $employee_obj, $data);
                $to = $this->getValues('undertime', $employee_obj, $data);
                $type = $this->getValues('type', $employee_obj, $data);

                $att->setUnderTime($to);
                $att->setStatus($type);
                $att->setLate($late);
            }
            $em->persist($att);
            $em->flush();

            $resp = new Response('insert_success');
        }
        catch (Exception $e)
        {
            $resp = new Response('no_out');
        }
        return $resp;
    }

    private function getEmployeeIDs($date)
    {
        $em = $this->getDoctrine()->getManager();
        $employee_ids = array();
        $entries = $em->getRepository('HrisBiometricsBundle:BiometricsAttendance')->findBy(array('date' => $date));
        foreach ($entries as $entry) 
        {
            array_push($employee_ids, $entry->getEmployee()->getID());
        }
        //get unique IDs
        return $employee_ids = array_unique($employee_ids);
    }

    private function backTrackAttendance($employee_obj, $data)
    {
        $em = $this->getDoctrine()->getManager();
        $late = $this->getValues('late', $employee_obj, $data);
        $to = $this->getValues('undertime', $employee_obj, $data);
        $type = $this->getValues('type', $employee_obj, $data);
        //search for time-in then update time-out
        $dateYesterday = new DateTime($data['DATE']);
        $dateYesterday = $dateYesterday->sub( new DateInterval('P1D'));
        $attendance_obj = $em->getRepository('HrisWorkforceBundle:Attendance')->findOneBy(array('date' => $dateYesterday, 'employee' => $employee_obj, 'time_out' => null));
        if ($attendance_obj != null) 
        {
            $attendance_obj->setTimeOut(new DateTime($data['TIME_OUT']));
            $attendance_obj->setUnderTime($to);
            $attendance_obj->setStatus($type);
            $attendance_obj->setLate($late);
            $em->flush();
            return 'insert_success';
        }
        else
        {
            return 'dupe';
        }
    }

    //for mdb source
    public function beginTransmissionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();
        $am = $this->get('hris_attendance');

        if (isset($data['OPERATION'])) 
        {
            $OPERATION = $data['OPERATION'];
        }
        else
        {
            $OPERATION = "";
        }

        //CHECK IF TOKEN IS VALID
        $token_obj = $em->getRepository('HrisBiometricsBundle:AuthToken')->findOneBy(array('token' => $data['TOKEN'], 'date' => new DateTime() ));

        if ($token_obj != null) 
        {
            $employee_obj = $em->getRepository('HrisWorkforceBundle:Employee')->findOneBy(array('employee_code' => $data['ID'] ));
            $user_obj = $em->getRepository('CatalystUserBundle:User')->findOneBy(array('username' => "admin" ));
            if ($employee_obj != null) 
            {
                if ($OPERATION == "backtrack")
                {
                    $resp = $this->backTrackAttendance($employee_obj, $data);
                }
                elseif ($OPERATION == "no_out")
                {
                    try
                    {
                        $att = new Attendance();
                        $att->setDate(new DateTime($data['DATE']));
                        $att->setEmployee($employee_obj);
                        $att->setTimeIn(new DateTime($data['TIME_IN']));
                        $att->setUserCreate($user_obj);
                        $em->persist($att);
                        $em->flush();

                        $resp = new Response('insert_success');
                    }
                    catch (Exception $e)
                    {
                        $resp = new Response('no_out');
                    }
                }
                else
                {
                    $late = $this->getValues('late', $employee_obj, $data);
                    $to = $this->getValues('undertime', $employee_obj, $data);
                    $type = $this->getValues('type', $employee_obj, $data);

                    try
                    {
                        $att = new Attendance();
                        $att->setDate(new DateTime($data['DATE']));
                        $att->setEmployee($employee_obj);
                        $att->setTimeIn(new DateTime($data['TIME_IN']));
                        if ($data['TIME_OUT'] != null && $data['TIME_OUT'] != 'null') 
                        {
                            $att->setTimeOut(new DateTime($data['TIME_OUT']));
                        }
                        $att->setUserCreate($user_obj);
                        $att->setUnderTime($to);
                        $att->setStatus($type);
                        $att->setLate($late);
                        $em->persist($att);
                        $em->flush();

                        $resp = new Response('insert_success');
                    }
                    catch (Exception $e)
                    {
                        $resp = new Response('dupe');
                    }
                }
            }
            else
            {
                $resp = new Response('Employee not found'.$data['ID']);
            }         
        }
        else
        {
            $resp = new Response('inv_token');
        }
        return $resp;
    }

    private function getValues($value, $employee_obj, $data)
    {   
        $new_in = 0;
        $new_out = 0;
        $time_in = 0;
        $time_out = 0;
        $late = 0;
        $to = 0;
        $am = $this->get('hris_attendance');
        $em = $this->getDoctrine()->getManager();
        $schedule = $em->getRepository('HrisAdminBundle:Schedules')->find($employee_obj->getSchedule());
        $start_sched = $schedule->getStart();
        $end_sched = $schedule->getEnd();
        $type = $am->checkHolidayType($employee_obj,new DateTime($data['DATE']));

        if (isset($data['TIME_IN'])) {

            //FOR TIME-IN
            if ($data['TIME_IN'] != null && $data['TIME_IN'] != 'null') 
            {
                $new_in = new DateTime($data['TIME_IN']);
                $time_in = (strtotime($new_in->format('g:i A')) - strtotime($start_sched)) / 60;
            }
            
            //COMPUTATION FOR TIME-OUT
            if ($schedule->getType() == 'semi-flexi' || $schedule->getType() == 'flexi') {
                //for semi-flexi
                if ($data['TIME_OUT'] != null && $data['TIME_OUT'] != 'null') 
                {   
                    $new_out = new DateTime($data['TIME_OUT']);
                    //compute [required hours] and calculate for new $end_sched
                    $required_hours = $schedule->getRequiredHours()*60;
                    //new end_sched
                    $start_schedx = new DateTime($start_sched);
                    $end_sched = $start_schedx->add(new DateInterval('PT'.$required_hours.'M'));
                    //add condition if end sched less than core end, set end sched to core end
                    $time_out = (strtotime($new_out->format('g:i A')) - strtotime($end_sched->format('g:i A'))) / 60;
                }
            } else {
                //for fixed
                if ($data['TIME_OUT'] != null && $data['TIME_OUT'] != 'null') 
                {   
                    $new_out = new DateTime($data['TIME_OUT']);
                    $time_out = (strtotime($new_out->format('g:i A')) - strtotime($end_sched)) / 60;
                }
            }

            

            if(intval($time_in) > 0 && ($schedule->getType() != 'flexi'))
            {
                if ($time_in <= $schedule->getGracePeriod())
                {
                    $late = 0;
                } 
                else
                {
                    //getHalfDay is minutes allowed before set as half day
                    if($time_in >= $schedule->getHalfday())
                    {
                        $type = Attendance::STATUS_HALFDAY;
                    }
                    $late = $time_in;
                }
            }
            else
            {
                $late = 0;
            }

            if($time_out < 0)
                $to = abs($time_out);
             else
                 $to = 0;

            if ($value == "late") 
            {
                //no late for flexible
                return $late;
            }
            elseif ($value == "type")
            {
                return $type;
            }
            elseif ($value == "undertime")
            {
                return $to;
            }
            //
        }
        
    }
}

