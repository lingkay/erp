<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Hris\AdminBundle\Entity\Bonus;
use Hris\AdminBundle\Utility\BonusTypes;
use Hris\AdminBundle\Utility\ManagerGroup;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\AdminBundle\Entity\TaxMatrix;
use DateTime;

class BonusController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_payroll_settings_bonus';
        $this->title = 'Bonus';
        $this->list_title = 'Bonus';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
        return new Bonus();
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setName($data['name']);

        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('HrisAdminBundle:BonusTypes')->findOneById($data['type']);
        $giver = $em->getRepository('GistUserBundle:User')->findOneById($data['giver']);

        $o->setAuthorizedGiver($giver);
        $o->setType($type);
    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();
        $params['holiday_opts'] = array('Company Event' => 'Company Event', 'Regular Holiday' => 'Regular Holiday', 'Special Non-Working' => 'Special Non-Working', 'Others' => 'Others');
        $params['type_options'] = $this->getTypeOptions();
        $params['giver_options'] = $this->getGiverOptions();
        return $params;
    }

    public function getTypeOptions($filter = array())
    {
        $em = $this->getDoctrine()->getManager();
        $whs = $em
            ->getRepository('HrisAdminBundle:BonusTypes')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        $bonusTypeOptions = array();
        foreach ($whs as $wh)
            $bonusTypeOptions[$wh->getID()] = $wh->getName();

        return $bonusTypeOptions;
    }

    public function getGiverOptions($filter = array())
    {
        $em = $this->getDoctrine()->getManager();
        $whs = $em
            ->getRepository('GistUserBundle:User')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        $giverOptions = array();
        foreach ($whs as $wh) {
            if ($wh->getGroup()->getID() == ManagerGroup::MANAGER_GROUP_ID || $wh->getGroup()->getID() == ManagerGroup::ASSISTANT_MANAGER_GROUP_ID) {
                $giverOptions[$wh->getID()] = $wh->getDisplayName();
            }
        }

        return $giverOptions;
    }

    public function editFormAction($id)
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->find($id);

        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');



        $this->padFormParams($params, $obj);

        return $this->render('HrisAdminBundle:Bonus:form.html.twig', $params);
    }

    protected function getObjectLabel($obj)
    {
        if ($obj == null){
            return '';
        }
        return $obj->getID();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Name','getName','name'),
        );
    }
}
