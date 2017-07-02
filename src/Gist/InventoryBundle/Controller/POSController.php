<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\Brand;
use Gist\ValidationException;

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
}
