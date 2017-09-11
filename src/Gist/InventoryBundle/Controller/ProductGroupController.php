<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\ProductGroup;
use Gist\InventoryBundle\Entity\Product;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductGroupController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'cat_inv_pg';
        $this->title = 'Group';

        $this->list_title = 'Groups';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new ProductGroup();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getName();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Name', 'getName', 'name'),
        );
    }

    protected function update($o, $data, $is_new = false)
    {
        // validate name
        if (strlen($data['name']) > 0)
            $o->setName($data['name']);
        else
            throw new ValidationException('Cannot leave name blank');
    }

    public function ajaxGetProductsAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $pg = $em->getRepository('GistInventoryBundle:ProductGroup')->find($id);
        $prods = $pg->getProducts();

        $data = array();
        foreach($prods as $p)
        {
            $data[] = [
                'id' => $p->getID(),
                'name' => $p->getName(),
            ];
        }

        return new JsonResponse($data);
    }

    public function ajaxGetSupplierProductsAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $supp_prods = $em->getRepository('GistInventoryBundle:SupplierProduct')->findBy(array('supplier'=>$id));
        
        //$prods = $pg->getProducts();

        $data = array();
        foreach($supp_prods as $p)
        {
            $data[] = [
                'id' => $p->getProduct()->getID(),
                'name' => $p->getProduct()->getName(),
                'price' => $p->getPrice(),
            ];
        }

        return new JsonResponse($data);
    }

    public function ajaxGetRawMaterialsAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $pg = $em->getRepository('GistInventoryBundle:ProductGroup')->find($id);
        $prods = $em->getRepository('GistInventoryBundle:Product')->findBy(array(
                'prodgroup' => $pg,
                'type_id' => Product::TYPE_RAW_MATERIAL
            ));

        $data = array();
        foreach($prods as $p)
        {
            $data[] = [
                'id' => $p->getID(),
                'name' => $p->getName(),
            ];
        }

        return new JsonResponse($data);
    }

}


