<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Hris\AdminBundle\Entity\TaxMatrixTable;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\AdminBundle\Entity\TaxMatrix;
use DateTime;

class TaxMatrixController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_admin_tax_matrix';
        $this->title = 'Tax Matrix';
        $this->list_title = 'Tax Matrix';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
        return new TaxMatrix();
    }

    protected function update($o, $data, $is_new = false)
    {
        //        echo "<pre>";
//        var_dump($data);
//        echo "</pre>";
//        die();
        $em = $this->getDoctrine()->getManager();
        $o->setIsAmountPercent($data['amt_type']);
        $o->setName($data['name']);

        $em->persist($o);
        $em->flush();

        $taxMatrixTable = $em->getRepository('HrisAdminBundle:TaxMatrixTable')->findBy(array('tax'=>$o->getID()));
        foreach ($taxMatrixTable as $entry) {
            $em->remove($entry);
            $em->flush();
        }

        if (isset($data['to'])) {
            foreach ($data['to'] as $i => $amountTo) {
                //if (trim($data['from'][$i]) != '' && trim($data['to'][$i]) != '' && trim($data['amount'][$i]) != '') {
                    $matrixEntry = new TaxMatrixTable();
                    $matrixEntry->setTaxMatrix($o);
                    $matrixEntry->setAmountFrom($data['from'][$i]);
                    $matrixEntry->setAmountTo($data['to'][$i]);
                    $matrixEntry->setTaxAmount($data['amount'][$i]);
                    $em->persist($matrixEntry);
                    $em->flush();
                //}
            }
        }


    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();
        $params['amt_opts'] = array('0' => 'Number', '1' => 'Percentage');

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

        return $this->render('HrisAdminBundle:TaxMatrix:form.html.twig', $params);
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
