<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\AdminBundle\Entity\JobLevel;

class JobLevelController extends CrudController
{
	
	use TrackCreate;

	public function __construct()
	{
		$this->route_prefix = 'hris_admin_joblevel';
		$this->title = 'Job Level';

		$this->list_title = 'Job Level';
		$this->list_type = 'dynamic';
	}

	protected function newBaseClass()
	{
		return new JobLevel();
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
			$grid->newColumn('Job Level','getName','name'),
		);
	}


    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $o->setName($data['name']);
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
            if($position->getDepartment()->getID() == $id)
            {
                $list_opts[] = array('id' => $position->getID(), 'name' => $position->getName());
            }
        }
        return new JsonResponse($list_opts);   
    }
}