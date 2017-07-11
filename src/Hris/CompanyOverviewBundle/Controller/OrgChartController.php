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
    	$query = "SELECT e FROM GistUserBundle:User e WHERE e.group = :group";

    	$employees = $em->createQuery($query)
                        ->setParameter('group', $id)
    					->getResult();
    	
        $department = $settings->getDepartment($id);
    	$list = [];
        $employee_information = null;
        $parent = 0;
    	foreach($employees as $employee)
    	{
            $parent = 0;
            
            $picture = "<img src='/bundles/hristemplate/images/no_photo.png' style='width: 80%; height: 80%;'>";
            
            $employee_information = '<div><span style="font-size: 100%;"><strong>'.' '.$employee->getDisplayName().'</strong></span><br><span style="font-style: italic; font-size: 90%;">'.$employee->getGroup()->getName().'</span></div>';

    		$list[] = array('id' => $employee->getID(), 'name' => $picture, 'description' => $employee_information, 'parent' => $parent);
    	}
        $resp = new Response(json_encode($list));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function getDepartmentHierarchyAction()
    {
    	$em = $this->getDoctrine()->getManager();

    	$departments = $em->getRepository('GistUserBundle:Group')->findAll();
    	
    	$list = [];
        $list[] = array('id' => -1, 'name' => "Cosmeti", 'description' => '', 'parent' => 0);
    	foreach($departments as $department)
    	{
    		if($department->getParent() == NULL || $department->getParent() == 'null')
    		{
    			$parent = -1;
    		}
    		else
    		{
    			$parent = $department->getParent()->getID();
    		}
            $dept_head = "";
            
    		$list[] = array('id' => $department->getID(), 'name' => $department->getName(), 'description' => $dept_head, 'parent' => $parent);
    	}
        $resp = new Response(json_encode($list));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function newBaseClass() {
        
    }
}