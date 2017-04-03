<?php

namespace Hris\WorkforceBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\WorkforceBundle\Entity\Appraisal;
use Hris\WorkforceBundle\Entity\Employee;
use Hris\WorkforceBundle\Entity\Evaluator;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;

use Symfony\Component\HttpFoundation\JsonResponse;

use DateTime;

class AppraisalController extends CrudController
{
    use TrackCreate;
	public function __construct()
	{
		$this->route_prefix = 'hris_workforce_appraisals';
		$this->title = 'Appraisal';

		$this->list_title = 'Appraisals';
		$this->list_type = 'dynamic';
	}

    protected function newBaseClass()
    {
        return new Appraisal();
    }

    public function callbackGrid($id)
    {
        $params = array(
            'id' => $id,
            'route_edit' => $this->getRouteGen()->getEdit(),
            'route_delete' => $this->getRouteGen()->getDelete(),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'HrisWorkforceBundle:Appraisal:action.html.twig',
            $params
        );
    }

	public function indexAction()
	{
        $this->checkAccess($this->route_prefix . '.view');

		$this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_workforce_appraisals_index');

        $twig_file = 'HrisWorkforceBundle:Appraisal:index.html.twig';

        $params = $this->getViewParams('List');

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        return $this->render($twig_file, $params);			
	}

    public function addFormAction()
    {
        $this->checkAccess($this->route_prefix . '.add');

        $this->hookPreAction();
        $obj = $this->newBaseClass();

        $params = $this->getViewParams('Add');
        $params['object'] = $obj;

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');
        $this->padFormParams($params, $obj);

        return $this->render('HrisWorkforceBundle:Appraisal:form.html.twig', $params);
    }

    public function editFormAction($id)
    {
        $this->checkAccess($this->route_prefix . '.view');
        $am = $this->get('hris_appraisal');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->find($id);

        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);

        $start_date = $obj->getDateStart();
        $end_date = $obj->getDateEnd();

        $params['start'] = $start_date->format('Y-m-d');
        $params['end'] = $end_date->format('Y-m-d');

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');
        $params['overall_status'] = $am->getAppraisalOverallStatus($id);
        $params['overall_grade'] = $am->getAppraisalGrade($id);

        $this->padFormParams($params, $obj);

        $params['evals'] = $am->getEvaluators($id);

        return $this->render('HrisWorkforceBundle:Appraisal:view.html.twig', $params);
    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();

        $emps = $em->getRepository('HrisWorkforceBundle:Employee')->findAll();
        $depts = $em->getRepository('HrisAdminBundle:Department')->findAll();
        $presets = $em->getRepository('HrisAdminBundle:AppraisalSettings')->findAll();

        $emp_list = array();
        foreach ($emps as $emp)
            $emp_list[$emp->getID()] = $emp->getFirstName().' '.$emp->getLastName();

        $preset_list = array();
        foreach ($presets as $preset)
            $preset_list[$preset->getID()] = $preset->getName();

        $dept_list = array();
        foreach ($depts as $dept)
            $dept_list[$dept->getID()] = $dept->getName();

        $params['emp_list'] = $emp_list;
        $params['dept_list'] = $dept_list;
        $params['type_list'] = $this->getEvalType();
        $params['preset_list'] = $preset_list;

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();

        $em = $this->getDoctrine()->getManager();

        $emp = $em->getRepository('HrisWorkforceBundle:Employee')->find($data['emp_id']);
        $preset = $em->getRepository('HrisAdminBundle:AppraisalSettings')->find($data['appraisal_preset']);

        $o->setEmployee($emp)
            ->setDateStart(new DateTime($data['date_from']))
            ->setDateEnd(new DateTime($data['date_to']))
            ->setPreset($preset)
            ->setOverallQuali('N/A')
            ->setOverallQuanti('N/A');

        if ($data['type'] == 'PROMOTION') {
            $o->setType(Appraisal::PROMOTION);
        } elseif ($data['type'] == 'REGULARIZATION') {
            $o->setType(Appraisal::REGULARIZATION);
        } elseif ($data['type'] == 'QUARTER') {
            $o->setType(Appraisal::QUARTER);
        } elseif ($data['type'] == 'MERIT') {
            $o->setType(Appraisal::MERIT);
        } else {
            $o->setType(Appraisal::OTHERS);
        }
        $em->persist($o);

        if (isset($data['evaluator']))
        {
            foreach ($data['evaluator'] as $id => $name) {

                $e = new Evaluator();
                $evaluator = $em->getRepository('HrisWorkforceBundle:Employee')->find($id);

                $e->setAppraisal($o)
                    ->setEmployee($evaluator)
                    ->setStatus(Evaluator::PENDING)
                    ->setQualiRating(Evaluator::INCOMPLETE)
                    ->setQuantiRating(Evaluator::INCOMPLETE);

                $em->persist($e);
                $em->flush();
                $this->notifyEvaluator($e,$emp);
            }
        }

        $this->updateTrackCreate($o, $data, $is_new);
    }

    protected function notifyEvaluator($evaluator, $employee)
    {
        $event = new NotificationEvent();
        $event->notify(array(
            'source'=> 'New Evaluation',
            'link'=> $this->generateUrl('hris_workforce_appraisals_evaluate',array('eval'=> $evaluator->getID())),
            'message'=> $employee->getDisplayName().' is ready for evaluation. ',
            'type'=> Notification::TYPE_UPDATE,
            'receipient' => $evaluator->getEmployee()));

            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('notification.event', $event);
    }

    protected function getEvalType()
    {
        $eval_type = [];
        $eval_type = array(
            'REGULARIZATION' => Appraisal::REGULARIZATION ,
            'PROMOTION' => Appraisal::PROMOTION ,
            'QUARTER' => Appraisal::QUARTER ,
            'MERIT' => Appraisal::MERIT ,
            'OTHERS' => Appraisal::OTHERS ,
        );

        return $eval_type;
    }

    public function getEvalListAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $emp = $em->getRepository('HrisWorkforceBundle:Employee')->findAll();

        $json = array();
        foreach($emp as $e)
        {
            if($e->getDepartment()->getID() == $id)
            {
                $json[$e->getID()] = [
                'name' => $e->getFirstName().' '.$e->getLastName(),
                ]; 
            }
        }

        return new JsonResponse($json);   
    }

    public function evaluateAction($eval)
    {
        $params = $this->getViewParams('Edit');

        $em = $this->getDoctrine()->getManager();
        $eval = $em->getRepository('HrisWorkforceBundle:Evaluator')->find($eval); 
        $obj = $em->getRepository('HrisWorkforceBundle:Appraisal')->find($eval->getAppraisal()->getID());
        $start_date = $obj->getDateStart();
        $end_date = $obj->getDateEnd();
        
        $emp_data = $em->getRepository('HrisWorkforceBundle:Employee')->find($obj->getEmployee()->getID());

        $params = $this->getViewParams('', 'hris_workforce_appraisals_evaluate');

        $twig_file = 'HrisWorkforceBundle:Appraisal:evaluate.html.twig';

        $params['eval'] = $eval;
        $params['eval_name'] = $this->getUser()->getName();
        $params['eval_email'] = $this->getUser()->getEmail();
        $params['emp_data'] = $emp_data;
        $params['eval_type'] = $this->getEvalType();
        $params['eval_start'] = $start_date->format('Y-m-d');
        $params['eval_end'] = $end_date->format('Y-m-d');
        $params['obj'] = $obj;

        return $this->render($twig_file, $params);
    }

    public function evaluateSubmitAction($eval)
    {
        $this->checkAccess($this->route_prefix . '.evaluate');
        $this->hookPreAction();

        $data = $this->getRequest()->request->all();

        $em = $this->getDoctrine()->getManager();
        $eval = $em->getRepository('HrisWorkforceBundle:Evaluator')->find($eval);
        $obj = $em->getRepository('HrisWorkforceBundle:Appraisal')->find($eval->getAppraisal()->getID());

        $score = 0;
        foreach ($data['KPI'] as $id => $value) {
            if (isset($value['score'])) {
                $score = $score + $value['score'];
            }
        }

        foreach ($data['PQC'] as $id => $value) {
            if (isset($value['score'])) {
                $score = $score + $value['score'];
            }
        }

        $ratings = $em->getRepository('HrisWorkforceBundle:Rating')->findAll();
        
        $quali = '';
        foreach ($ratings as $grade) {
            if ($score >= $grade->getRangeStart() && $score <= $grade->getRangeEnd()) {
                $quali = $grade->getRating();
            }
        }

        $eval->setStatus(Evaluator::COMPLETED)
            ->setQualiRating($quali)
            ->setQuantiRating($score)
            ->setKPIDetails($data['KPI'])
            ->setPQCDetails($data['PQC'])
            ->setComments($data['comment'])
            ->setDateEvaluated(new DateTime());

        $em->persist($eval);
        $em->flush();
    
        $am = $this->get('hris_appraisal');

        $obj->setOverallQuali($am->getAppraisalRating($obj->getID()))
            ->setOverallQuanti($am->getAppraisalGrade($obj->getID()));

        $em->persist($obj);
        $em->flush();
        
        $id = $eval->getAppraisal()->getID();

        $this->addFlash('success', 'Evaluation for ' . $this->getObjectLabel($obj) . ' completed successfully.');

        return $this->redirect($this->generateUrl('hris_workforce_appraisals_edit_form', array('id' => $id)));
    }

    protected function deleteEvaluators($id)
    {
        $em = $this->getDoctrine()->getManager();

        $evaluator = $em->getRepository('HrisWorkforceBundle:Evaluator')->findBy(
                array('appraisal' => $id)
            );

        foreach ($evaluator as $e) {
            $em->remove($e);
        }

        $em->flush();
    }

    public function deleteAction($id)
    {
        $this->checkAccess($this->route_prefix . '.delete');

        try
        {
            $this->hookPreAction();
            $em = $this->getDoctrine()->getManager();

            $object = $em->getRepository($this->repo)->find($id);
            $odata = $object->toData();
            $this->logDelete($odata);

            $this->deleteEvaluators($id);

            $em->remove($object);
            $em->flush();

            $this->addFlash('success', $this->title . ' ' . $this->getObjectLabel($object) . ' has been deleted.');

            return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
        }
        catch (DBALException $e)
        {
            $this->addFlash('error', $e->getMessage());

            $this->addFlash('error', 'Could not delete ' . $this->title . '.');
            return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
        }
    }

	protected function getObjectLabel($obj) {
        if ($obj == null){
            return '';
        }
        return $obj->getEmployeeName();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Employee Name', 'getEmployeeName', 'employee'),
            $grid->newColumn('Type', 'getType', 'type'),
            $grid->newColumn('Date Start', 'getDateStart', 'date_start', 'o', array($this,'formatDate')),
            $grid->newColumn('Date End', 'getDateEnd', 'date_end', 'o', array($this,'formatDate')),
        );
    }    
}