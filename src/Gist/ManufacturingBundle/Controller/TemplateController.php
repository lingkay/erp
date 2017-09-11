<?php

namespace Gist\ManufacturingBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ManufacturingBundle\Entity\BOMTemplate;
use Gist\ManufacturingBundle\Entity\BOMDimension;
use Gist\ManufacturingBundle\Entity\BOMMaterial;
use Gist\ManufacturingBundle\Model\TemplateGenerator;
use Gist\ValidationException;

class TemplateController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'cat_mfg_tpl';
        $this->title = 'Template';

        $this->list_title = 'Templates';
        $this->list_type = 'dynamic';
        $this->repo = 'GistManufacturingBundle:BOMTemplate';
    }

    protected function newBaseClass()
    {
        return new BOMTemplate();
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
            $grid->newColumn('Output Code', 'getOutputCode', 'output_code'),
            $grid->newColumn('Output Name', 'getOutputName', 'output_name'),
        );
    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();

        $inv = $this->get('gist_inventory');

        $params['prod_opts'] = $inv->getProductOptions();
        $params['pg_opts'] = $inv->getProductGroupOptions();

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();

        $o->setName($data['name']);
        $o->setOutputCode($data['output_code']);
        $o->setOutputName($data['output_name']);
        $o->setUnitOfMeasure($data['uom']);

        $pg = $em->getRepository('GistInventoryBundle:ProductGroup')->find($data['prodgroup_id']);
        if ($pg == null)
            throw new ValidationException('Product group could not be found.');

        $o->setProductGroup($pg);
            

        // dimensions
        $dims = $o->getDimensions();
        foreach ($dims as $dim)
            $em->remove($dim);

        $o->clearDimensions();
        if (isset($data['dim_name']))
        {
            foreach ($data['dim_name'] as $index => $name)
            {
                // add
                $shortcode = $data['dim_short_code'][$index];
                $o->addDimension(new BOMDimension($name, $shortcode));
            }
        }

        // materials
        $mats = $o->getMaterials();
        foreach ($mats as $mat)
            $em->remove($mat);

        $o->clearMaterials();
        if (isset($data['mat_id']))
        {
            $prod_repo = $em->getRepository('GistInventoryBundle:Product');
            foreach ($data['mat_id'] as $index => $id)
            {
                // get product
                $product = $prod_repo->find($id);
                if ($product == null)
                    continue;

                $formula = $data['mat_formula'][$index];

                // initialize
                $mat_obj = new BOMMaterial();
                $mat_obj->setProduct($product)
                    ->setFormula($formula);

                // add
                $o->addMaterial($mat_obj);
            }
        }
    }

    public function generateAction($id)
    {
        $log = $this->get('gist_log');
        $data = $this->getRequest()->request->all();
        $em = $this->getDoctrine()->getManager();

        // template generator
        $template = $em->getRepository($this->repo)->find($id);
        $tgen = new TemplateGenerator($template);

        // persist the product and bom
        $gen_output = $tgen->generate($em, $data);
        foreach ($gen_output as $gen)
            $em->persist($gen);
        $em->flush();

        // log generated product
        $odata_prod = $gen_output['product']->toData();
        $log->log('cat_inv_prod_add', 'generated Product ' . $odata_prod->id . ' from template ' . $template->getID() . '.', $odata_prod);

        // log generated bom
        $odata_bom = $gen_output['bom']->toData();
        $log->log('cat_mfg_bom_add', 'generated BOM ' . $odata_bom->id . ' from template ' . $template->getID() . '.', $odata_bom);

        // log template
        $odata = new \stdClass();
        $odata->template = $template->toData();
        $odata->params = $data;
        $odata->product = $odata_prod;
        $odata->bom = $odata_bom;
        $log->log('cat_mfg_tpl_generate', 'generated BOM Template ' . $template->getID() . '.', $odata);

        // add flash message
        $this->addFlash('success', 'Product and Bill of Materials generated for template ' . $template->getName() . '.');

        return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
    }
}
