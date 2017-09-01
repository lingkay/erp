<?php

namespace Gist\POSERPBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\POSERPBundle\Entity\POSTransaction;
use Gist\POSERPBundle\Entity\POSTransactionItem;
use Gist\POSERPBundle\Entity\POSTransactionPayment;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class POSSyncController extends CrudController
{
    protected function newBaseClass()
    {
        return new POSTransaction();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getID();
    }

    public function saveTransactionAction($id, $display_id, $total, $balance, $type, $customer_id, $status, $tax_rate, $orig_vat_amt, $new_vat_amt, $orig_amt_net_vat, $new_amt_net_vat, $tax_coverage, $cart_min, $orig_cart_total, $new_cart_total,$bulk_type,$transaction_mode)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();


        // REMOVE EXISTING TRANSACTION
        $existing_transactions = $em->getRepository('GistPOSERPBundle:POSTransaction')->findBy(array('trans_display_id'=>$display_id));   
        foreach ($existing_transactions as $et) {
            // REMOVE PAYMENTS
            foreach ($et->getItems() as $item) {
                $em->remove($item);
            }
            // REMOVE ITEMS
            foreach ($et->getPayments() as $payment) {
                $em->remove($payment);
            }
            $em->flush();
            $em->remove($et);
            $em->flush();
        }
        



        $transaction = new POSTransaction();

        if (trim($customer_id) != 0 || trim($customer_id) != '') {
            $cust_obj = $em->getRepository('GistCustomerBundle:Customer')->findOneBy(array('id'=>$customer_id));   
            $transaction->setCustomer($cust_obj);
        }
        

        $transaction->setId($id);
        $transaction->setTransDisplayId($display_id);
        $transaction->setCustomerId($customer_id);
        $transaction->setTransactionBalance($balance);
        $transaction->setTransactionTotal($total);
        $transaction->setTransactionType($type);
        $transaction->setStatus($status);
        $transaction->setSyncedToErp('true');
        $transaction->setTransactionMode($transaction_mode);

        $transaction->setTaxRate($tax_rate);
        $transaction->setOrigVatAmt($orig_vat_amt);
        $transaction->setNewVatAmt($new_vat_amt);
        $transaction->setOrigAmtNetVat($orig_amt_net_vat);
        $transaction->setNewAmtNetVat($new_amt_net_vat);
        $transaction->setTaxCoverage($tax_coverage);
        $transaction->setCartMin($cart_min);
        $transaction->setCartOrigTotal($orig_cart_total);
        $transaction->setCartNewTotal($new_cart_total);
        $transaction->setBulkDiscountType($bulk_type);



        $em->persist($transaction);
        $em->flush();

        $list_opts[] = array('status'=>$transaction->getStatus(),'new_id'=>$transaction->getID());
        return new JsonResponse($list_opts);
    }

    public function saveTransactionItemsAction($trans_sys_id, $prod_id, $prod_name, $orig_price, $min_price, $adjusted_price, $discount_type, $discount_value)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $transaction_item = new POSTransactionItem();

        $transaction = $em->getRepository('GistPOSERPBundle:POSTransaction')->findOneBy(array('trans_display_id'=>$trans_sys_id));
        
        $transaction_item->setTransaction($transaction);
        $transaction_item->setProductId($prod_id);
        $transaction_item->setOrigPrice($orig_price);
        $transaction_item->setMinimumPrice($min_price);
        $transaction_item->setAdjustedPrice($adjusted_price);
        $transaction_item->setName($prod_name);
        $transaction_item->setDiscountType($discount_type);
        $transaction_item->setDiscountValue($discount_value);

        $em->persist($transaction_item);
        $em->flush();

        $list_opts[] = array('status'=>'ok');
        return new JsonResponse($list_opts);
    }

    public function saveTransactionPaymentsAction($trans_sys_id, $payment_type, $amount)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $transaction_payment = new POSTransactionPayment();

        $transaction = $em->getRepository('GistPOSERPBundle:POSTransaction')->findOneBy(array('trans_display_id'=>$trans_sys_id));
        
        $transaction_payment->setTransaction($transaction);
        $transaction_payment->setType($payment_type);
        $transaction_payment->setAmount($amount);

        $em->persist($transaction_payment);
        $em->flush();

        $list_opts[] = array('status'=>'ok');
        return new JsonResponse($list_opts);
    }
}
