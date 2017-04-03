<?php

namespace Hris\CompanyOverviewBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Hris\WorkforceBundle\Entity\Employee;
use Gist\ValidationException;

class OrgChartController extends CrudController
{
	public function __construct()
	{
		$this->route_prefix = 'hris_com_orgchart';
		$this->title = 'Organizational Chart';

		$this->list_title = 'Organizational Chart';
		$this->list_type = 'static';
	}

	public function indexAction()
	{
        $this->checkAccess($this->route_prefix . '.view');
		$this->title = 'Organizational Chart';
		$params = $this->getViewParams('', 'hris_com_orgchart_index');

		return $this->render('HrisCompanyOverviewBundle:OrgChart:index.html.twig', $params);
	}

	protected function getObjectLabel($object)
	{
        if ($obj == null){
            return '';
        }
        return '';        
    }

    public function getEmployeeHierarchyAction($id)
    {
        $settings = $this->get('hris_settings');
    	$em = $this->getDoctrine()->getManager();
    	$query = "SELECT e FROM HrisWorkforceBundle:Employee e WHERE e.employment_status != :status AND e.department = :department";

    	$employees = $em->createQuery($query)
    					->setParameter('status', Employee::EMP_CONTRACTUAL)
                        ->setParameter('department', $id)
    					->getResult();
    	
        $department = $settings->getDepartment($id);
    	$list = [];
        $employee_information = null;
        $parent = 0;
    	foreach($employees as $employee)
    	{
            if($department->getDeptHead()->getID() == $employee->getID())
            {
                $parent = 0;
            }
            elseif($employee->getSupervisor() != null)
            {
                $parent = $employee->getSupervisor()->getID();
            } 

            if($employee->getProfile()->getUpload() == null || $employee->getProfile()->getUpload() == 'null')
            {
                $picture = "<img src='/bundles/hristemplate/images/no_photo.png' style='width: 80%; height: 80%;'>";
            }
            else
            {
                $picture = '<img src="'.$employee->getProfile()->getUpload()->getURL().'" style="width: 65%; height: 65%;">';
            }
            $employee_information = '<div><span style="font-size: 100%;"><strong>'.' '.$employee->getDisplayName().'</strong></span><br><span style="font-style: italic; font-size: 90%;">'.$employee->getJobTitle()->getName().'</span></div>';

    		$list[] = array('id' => $employee->getID(), 'name' => $picture, 'description' => $employee_information, 'parent' => $parent);
    	}
        $resp = new Response(json_encode($list));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function getDepartmentHierarchyAction()
    {
    	$em = $this->getDoctrine()->getManager();

    	$departments = $em->getRepository('HrisAdminBundle:Department')->findAll();
    	
    	$list = [];
    	foreach($departments as $department)
    	{
    		if($department->getParent() == NULL || $department->getParent() == 'null')
    		{
    			$parent = 0;
    		}
    		else
    		{
    			$parent = $department->getParent()->getID();
    		}

            if($department->getDeptHead() == NULL || $department->getDeptHead() == 'null')
            {
                // 
                $dept_head = "<i>Not Available</i>";
            }
            else
            {
                $dept_head = '<br>'.'<span style="font-style: italic;"><strong>'.$department->getDeptHead()->getDisplayName().'</strong></span>';
            }
    		$list[] = array('id' => $department->getID(), 'name' => $department->getName(), 'description' => $dept_head, 'parent' => $parent);
    	}
        $resp = new Response(json_encode($list));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function newBaseClass() {
        
    }
}