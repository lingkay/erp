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

    public function getProductsAction($pos_loc_id, $category_id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();

        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));
        $brands = explode(',', $pos_location->getBrand());

        //var_dump($brands);
        //die();

        $products = $em->getRepository('GistInventoryBundle:Product')->findBy(array('category'=>$category_id));
        $config = $this->get('gist_configuration');
        $vat = $em->getRepository('GistPOSERPBundle:POSSettings')->findOneBy(array('name'=>'Tax Mode'));
        if (count($vat) == 0) {
            $vat = 'incl';
        } else {
            $vat = $vat->getValue();
        }
        
        $vat_rate = $em->getRepository('GistPOSERPBundle:POSSettings')->findOneBy(array('name'=>'Tax Rate'));
        if (count($vat_rate) == 0) {
            $vat_rate = '12';
        } else {
            $vat_rate = $vat_rate->getValue();
        }
        
        $list_opts = [];
        foreach ($products as $p) {
            $fixedAssetsBrandID = $config->get('gist_fixed_asset_brand');
            if ($p->getBrand()->getID() != $fixedAssetsBrandID && $p->getType()->getName() == 'Goods' && in_array($p->getBrand()->getName(), $brands)) {
                $srp = 0;
                $min = 0;
                $o_srp = 0;
                if ($vat == 'excl') {
                    $o_srp = round($p->getSRP(),2);
                    $srp = round($p->getSRP() + ($p->getSRP()*($vat_rate/100)),2);
                    $min = round($p->getMinPrice() + ($p->getMinPrice()*($vat_rate/100)),2);
                } elseif ($vat == 'incl') {
                    $o_srp = round($p->getSRP(),2);
                    $srp = round($p->getSRP(),2);
                    $min = round($p->getMinPrice(),2);
                } else {
                    $o_srp = round($p->getSRP(),2);
                    $srp = round($p->getSRP(),2);
                    $min = round($p->getMinPrice(),2);
                }

                if ($p->getPrimaryPhoto()) {
                    $list_opts[] = array('id'=>$p->getID(), 'name'=> $p->getName(), 'image_url'=>$p->getPrimaryPhoto()->getURL(), 'srp'=>$srp, 'min_price'=>$min, 'orig_srp'=>$o_srp, 'barcode' => $p->getBarcode(), 'item_code'=>$p->getItemCode());
                } else {
                    $list_opts[] = array('id'=>$p->getID(), 'name'=> $p->getName(), 'image_url'=>null, 'srp'=>$srp, 'min_price'=>$min, 'orig_srp'=>$o_srp, 'barcode' => $p->getBarcode(), 'item_code'=>$p->getItemCode());
                }
            }
        }
        return new JsonResponse($list_opts);
    }

    public function getAllProducts()
    {
        
    }

    public function getVATAction()
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $opt = $em->getRepository('GistPOSERPBundle:POSSettings')->findOneBy(array('name'=>'Tax Rate'));
        if (count($opt) > 0) {
            return new JsonResponse($opt->getValue());
        }
        //default value
        return new JsonResponse("12");

    }

    public function getTaxCoverageAction()
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $opt = $em->getRepository('GistPOSERPBundle:POSSettings')->findOneBy(array('name'=>'Tax Mode'));
        if (count($opt) > 0) {
            return new JsonResponse($opt->getValue());
        }
        //default value
        return new JsonResponse("incl");

    }

    public function getUpsellTimeAction()
    {
        header("Access-Control-Allow-Origin: *");
        $config = $this->get('gist_configuration');
        $var = $config->get('gist_pos_upsell_seconds');
        return new JsonResponse($var);
    }

    public function getRefundDaysAction()
    {
        header("Access-Control-Allow-Origin: *");
        $config = $this->get('gist_configuration');
        $var = $config->get('gist_pos_max_refund_days');
        return new JsonResponse($var);

    }

    public function getRefundCodeAction()
    {
        header("Access-Control-Allow-Origin: *");
        $config = $this->get('gist_configuration');
        $var = $config->get('gist_pos_refund_code');
        return new JsonResponse($var);

    }

    public function getExchangeRuleBelowAction()
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $opt = $em->getRepository('GistPOSERPBundle:POSSettings')->findOneBy(array('name'=>'Exhange with refund'));
        if (count($opt) > 0) {
            return new JsonResponse($opt->getValue());
        }
        //default value
        return new JsonResponse("False");

    }

    public function getMinimumDepositPercentageAction()
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $opt = $em->getRepository('GistPOSERPBundle:POSSettings')->findOneBy(array('name'=>'Minimum Deposit Percentage'));
        if (count($opt) > 0) {
            return new JsonResponse($opt->getValue());
        }
        //default value
        return new JsonResponse("20");

    }

    public function getBanksAction()
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $objects = $em->getRepository('GistAccountingBundle:Bank')
            ->findAll();

        $opts = array();
        foreach ($objects as $o)
            $opts[$o->getID()] = $o->getName();

        //return $opts;
        return new JsonResponse($opts);
    }
    //GistAccountingBundle:TerminalOperator
    public function getTerminalOperatorsAction()
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $objects = $em->getRepository('GistAccountingBundle:TerminalOperator')
            ->findAll();

        $opts = array();
        foreach ($objects as $o)
            $opts[$o->getID()] = $o->getName();

        //return $opts;
        return new JsonResponse($opts);
    }
}
