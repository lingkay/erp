<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Hris\AdminBundle\Entity\Incentive;
use Hris\AdminBundle\Entity\IncentiveMatrix;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\AdminBundle\Entity\TaxMatrix;
use DateTime;

class IncentiveController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_payroll_settings_incentive';
        $this->title = 'Incentive';
        $this->list_title = 'Incentive';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
        return new Incentive();
    }

    protected function update($o, $data, $is_new = false)
    {
//        echo "<pre>";
//        var_dump($data);
//        echo "</pre>";
//        die();
        $em = $this->getDoctrine()->getManager();
        $o->setTarget($data['target']);
        $o->setDailySales($data['daily_sales']);
        $o->setName($data['name']);

        $position = $em->getRepository('GistUserBundle:Group')->find($data['position']);
        $o->setGroup($position);

        $em->persist($o);
        $em->flush();

        $incentiveMatrix = $em->getRepository('HrisAdminBundle:IncentiveMatrix')->findBy(array('incentive'=>$o->getID()));
        foreach ($incentiveMatrix as $entry) {
            $em->remove($entry);
            $em->flush();
        }

        $period = $em->getRepository('HrisAdminBundle:IncentivePeriod')->findOneById($data['period']);
        $o->setPeriod($period);

        if (isset($data['to'])) {
            foreach ($data['to'] as $i => $amountTo) {
                if (trim($data['from'][$i]) != '' && trim($data['to'][$i]) != '' && trim($data['percent'][$i]) != '') {
                    $matrixEntry = new IncentiveMatrix();
                    $matrixEntry->setIncentive($o);
                    $matrixEntry->setAmountFrom($data['from'][$i]);
                    $matrixEntry->setAmountTo($data['to'][$i]);
                    $matrixEntry->setPercentAmount($data['percent'][$i]);
                    $em->persist($matrixEntry);
                    $em->flush();
                }
            }
        }

    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->get('gist_user');
        $params['holiday_opts'] = array('Company Event' => 'Company Event', 'Regular Holiday' => 'Regular Holiday', 'Special Non-Working' => 'Special Non-Working', 'Others' => 'Others');
        $params['position_opts'] = $um->getGroupOptions();

        $settings = $this->get('hris_settings');
        $params['period_opts'] = $settings->getIncentivePeriodOptions();
        return $params;
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

        return $this->render('HrisAdminBundle:Incentive:form.html.twig', $params);
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
            $grid->newColumn('Position','getPositionName','position'),
        );
    }
}
