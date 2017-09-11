<?php

namespace Gist\ManufacturingBundle\Model;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Gist\ManufacturingBundle\Entity\BOMTemplate;
use Gist\ManufacturingBundle\Entity\BOMInput;
use Gist\ManufacturingBundle\Entity\BOMOutput;
use Gist\ManufacturingBundle\Entity\BillOfMaterial;
use Gist\InventoryBundle\Entity\Product;

class TemplateGenerator
{
    protected $template;

    public function __construct(BOMTemplate $template)
    {
        $this->template = $template;
    }

    protected function checkDimensions($data)
    {
        $dims = $this->template->getDimensions();

        //  make sure all dimensions have a value
        foreach ($dims as $d)
        {
            $sc = $d->getShortcode();
            if (!isset($data[$sc]))
                return false;

            if (strlen(trim($data[$sc])) <= 0)
                return false;
        }

        return true;
    }

    protected function replaceDimensionVars($string, $dim_data)
    {
        $res_string = $string;
        foreach ($dim_data as $sc => $value)
        {
            $search = '[' . $sc . ']';
            $replace = trim($value);
            $res_string = str_replace($search, $replace, $res_string);
        }

        return $res_string;
    }

    protected function generateProduct($data)
    {
        $prod = new Product();

        // code
        $code_formula = $this->template->getOutputCode();
        $code = $this->replaceDimensionVars($code_formula, $data);

        // name
        $name_formula = $this->template->getOutputName();
        $name = $this->replaceDimensionVars($name_formula, $data);

        $prod->setCode($code)
            ->setName($name)
            ->setUnitOfMeasure($this->template->getUnitOfMeasure())
            ->setProductGroup($this->template->getProductGroup())
            ->setFlagSale(false)
            ->setFlagPurchase(false)
            ->setPriceSale(0.0)
            ->setPricePurchase(0.0);

        return $prod;
    }

    protected function generateBOM(Product $prod, $data)
    {
        $bom = new BillOfMaterial();

        // name
        $name_formula = $this->template->getOutputName();
        $name = $this->replaceDimensionVars($name_formula, $data);
        $bom->setName($name);

        // output
        $output = new BOMOutput();
        $output->setQuantity(1)
            ->setBillOfMaterial($bom)
            ->setProduct($prod);
        $bom->addOutput($output);

        // materials
        $lang = new ExpressionLanguage();
        $mats = $this->template->getMaterials();
        foreach ($mats as $mat)
        {
            // evaluate formula
            $formula = $this->replaceDimensionVars($mat->getFormula(), $data);
            $qty = $lang->evaluate($formula);

            // input
            $input = new BOMInput();
            $input->setQuantity($qty)
                ->setBillOfMaterial($bom)
                ->setProduct($mat->getProduct());
            $bom->addInput($input);
        }

        return $bom;
    }

    public function generate($em, $data = array())
    {
        // check dimensions
        if (!$this->checkDimensions($data))
            return null;

        // product
        $prod = $this->generateProduct($data);

        // bom
        $bom = $this->generateBOM($prod, $data);

        return array(
            'product' => $prod,
            'bom' => $bom
        );
    }
}
