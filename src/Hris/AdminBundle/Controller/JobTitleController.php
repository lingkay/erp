<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\AdminBundle\Entity\JobTitle;

class JobTitleController extends CrudController
{
	
	use TrackCreate;

	public function __construct()
	{
		$this->route_prefix = 'hris_admin_jobtitles';
		$this->title = 'Position';

		$this->list_title = 'Position';
		$this->list_type = 'dynamic';
	}

	protected function newBaseClass()
	{
		return new JobTitle();
	}

	protected function getObjectLabel($obj)
	{
		if ($obj == null)
		{
			return '';
		}
		return $obj->getName();
	}

	protected function getGridColumns()
	{
		$grid = $this->get('gist_grid');

		return array(
			$grid->newColumn('Job Title','getName','name'),
            $grid->newColumn('Reports To', 'getName', 'name', 'j'),
            $grid->newColumn('Department', 'getName', 'name', 'd'),

		);
	}

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('d', 'department', 'getDepartment'),
            $grid->newJoin('j', 'parent', 'getParent','left'),
        );
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');
        
        $params['job_opts'] = $settings->getJobTitleOptions();
        $params['depts'] = $settings->getDepartmentOptions();

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $dept = $em->getRepository('HrisAdminBundle:Department')->find($data['dept']);
        $settings = $this->get('hris_settings');

        $o->setName($data['name']);
        $o->setNotes($data['notes']);
        $o->setDepartment($dept);
        if(isset($data['parent']) && $data['parent'] != 0){
            $o->setParent($settings->getJobTitle($data['parent']));
        }
        $this->updateTrackCreate($o, $data, $is_new);
    }

	public function ajaxFilterJobTitleAction()
    {
        $data = $this->getRequest()->query->all();
        $em = $this->getDoctrine()->getManager();
        $query = $data['query'];

        $jobs = $em->getRepository("HrisAdminBundle:JobTitle")->createQueryBuilder('o')
           ->where('o.name LIKE :name')
           ->setParameter('name', "%".$query."%")
           ->getQuery()
           ->getResult();

        $list_opts = [];
        foreach ($jobs as $job) {
            $list_opts[] = array('id'=>$job->getID(), 'name'=> $job->getName());
        }
        return new JsonResponse($list_opts);
    }

    public function ajaxFilterPosbyDeptAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $positions = $em->getRepository('HrisAdminBundle:JobTitle')->findAll();

        $list_opts = [];
        foreach($positions as $position)
        {
            if($id == 0)
            {
               $list_opts[] = array('id' => $position->getID(), 'name' => $position->getName()); 
            }
            else if($position->getDepartment()->getID() == $id)
            {
                $list_opts[] = array('id' => $position->getID(), 'name' => $position->getName());
            }
        }
        return new JsonResponse($list_opts);   
    }
}