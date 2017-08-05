<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\Brand;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class POSController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'gist_inv_pos';
        $this->title = 'POS';

        $this->list_title = 'POS';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
        return new Brand();
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
        $o->setName($data['name']);
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $am = $this->get('gist_accounting');

        //$gl = $this->setupGridLoader();

        $params = $this->getViewParams('List');
        $params['product_categories'] = $em->getRepository('GistInventoryBundle:ProductCategory')->findAll();
        $params['products'] = $em->getRepository('GistInventoryBundle:Product')->findAll();


        $twig_file = 'GistInventoryBundle:POS:app.html.twig';


        $params['list_title'] = $this->list_title;
        //$params['grid_cols'] = $gl->getColumns();

        return $this->render($twig_file, $params);
    }

    public function getProductCategoriesAction()
    {   
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('GistInventoryBundle:ProductCategory')->findAll();
        $list_opts = [];
        foreach ($categories as $c) {
            if ($c->getPrimaryPhoto()) {
                $list_opts[] = array('id'=>$c->getID(), 'name'=> $c->getName(), 'image_url'=>$c->getPrimaryPhoto()->getURL());
            } else {
                $list_opts[] = array('id'=>$c->getID(), 'name'=> $c->getName(), 'image_url'=>null);
            }
            
        }
        return new JsonResponse($list_opts);
    }

    public function getProductsAction($category_id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('GistInventoryBundle:Product')->findBy(array('category'=>$category_id));
        $list_opts = [];
        foreach ($products as $p) {
            if ($p->getPrimaryPhoto()) {
                $list_opts[] = array('id'=>$p->getID(), 'name'=> $p->getName(), 'image_url'=>$p->getPrimaryPhoto()->getURL(), 'srp'=>$p->getSRP(), 'min_price'=>$p->getMinPrice());
            } else {
                $list_opts[] = array('id'=>$p->getID(), 'name'=> $p->getName(), 'image_url'=>null);
            }
            
        }
        return new JsonResponse($list_opts);
    }

    public function getAllProducts()
    {
        
    }

    public function getVATAction()
    {
        $list_opts = [];
        header("Access-Control-Allow-Origin: *");
        $config = $this->get('gist_configuration');
        $vat = $config->get('gist_acct_vat_percentage');
        $list_opts[] = array('vat_pct'=>$vat);
        return new JsonResponse($vat);

    }
}
