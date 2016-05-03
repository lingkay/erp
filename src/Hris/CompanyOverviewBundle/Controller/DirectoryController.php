<?php

namespace Hris\CompanyOverviewBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;

class DirectoryController extends CrudController
{
	public function __construct()
	{
		$this->route_prefix = 'hris_com_directory';
		$this->title = 'Directory';

		$this->list_title = 'Directory';
		$this->list_type = 'dynamic';
	}

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $params = $this->getViewParams('List');

		$this->padFormParams($params);

        $params['list_title'] = $this->list_title;

		return $this->render('HrisCompanyOverviewBundle:Directory:index.html.twig', $params);
    }


	protected function getObjectLabel($object) {
        
    }

    protected function newBaseClass() {
        
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        $settings = $this->get('hris_settings');

        $params['dept_opts'] = $settings->getDepartmentOptions();
        $params['title_opts'] = $settings->getJobTitleOptions();
       
        return $params;
    }

    public function ajaxfilterDirectoryAction($id = NULL, $department = NULL, $job_title = NULL)
    {
        $settings = $this->get('hris_settings');
        $wm = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $qry = [];

        $qry[] = "SELECT e FROM HrisWorkforceBundle:Employee e WHERE e.id > 0";
        if($id != 'null')
        {
            $emp_name = explode(',', $id);
            if(count($emp_name) == 1)
            {
                $qry[] = " e.first_name LIKE '%".trim($emp_name[0])."%' OR e.last_name LIKE '%".trim($emp_name[0])."%'";    
            }
            else
            {
                $qry[] = " e.first_name LIKE '%".trim($emp_name[1])."%' AND e.last_name LIKE '%".trim($emp_name[0])."%'";                
            }
            
        }

        if($department != 0)
        {
            if(count($qry) >= 2)
            {
                $qry[] = " e.department=".$department;    
            }
            else
            {
                $qry[] = " e.department=".$department;
            }
            
        }

        if($job_title > 0)
        {
            if(count($qry) >= 2)
            {
                $qry[] = "  e.job_title=".$job_title;   
            }
            else
            {
                $qry[] = " e.job_title=".$job_title;
            }
            
        }

        if (!empty($qry))
        {
            $query = implode(' AND ', $qry);
        }
        
        //$query = implode('', $qry);
        $employees = $em->createQuery($query)
                        ->getResult();
        $array = array();
        foreach ($employees as $employee) {
           $phones = $employee->getProfile()->getPhones();
           $contact = [];
           $primary_contact = null;
           $is_dept_head = null;
            foreach($phones as $phone)
            {
                if($phone->getIsPrimary() == 1 || $phone->getIsPrimary() == true )
                {
                    $primary_contact = $phone->getNumber();
                }
                $contact[] = $phone->getNumber();
            } 
            if($employee->getProfile()->getUpload() == null || $employee->getProfile()->getUpload() == 'null')
            {
                $picture = 'null';
            }
            else
            {
                $picture = $employee->getProfile()->getUpload()->getURL();
            }

            if ($employee->getDepartment()->getDeptHead() !== NULL) {
                if($employee == $employee->getDepartment()->getDeptHead()) {
                    $is_dept_head = true;
                }
                else {
                    $is_dept_head = false;
                }
            }
            else {
                $is_dept_head = false;
            }
            
            $array[] = array('emp_name' => $employee->getDisplayName(), 'contact_number' => implode(' | ',$contact), 'email' => $employee->getEmail(), 'position' => $employee->getJobTitle()->getName(), 'department' => $employee->getDepartment()->getName(), 'locations' => $employee->getLocation()->getName(), 'picture' => $picture, 'dept_head' => $is_dept_head);
        }
            
        $resp = new Response(json_encode($array));
        $resp->headers->set('Content-Type', 'application/json');
        return $resp;
    }
}