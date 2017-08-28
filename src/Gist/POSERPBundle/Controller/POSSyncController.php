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

    public function saveTransactionAction($id, $display_id, $total, $balance, $type, $customer_id, $status)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $transaction = new POSTransaction();

        $transaction->setId($id);
        $transaction->setTransDisplayId($display_id);
        $transaction->setCustomerId($customer_id);
        $transaction->setTransactionBalance($balance);
        $transaction->setTransactionTotal($total);
        $transaction->setTransactionType($type);
        $transaction->setStatus('Frozen');
        $transaction->setSyncedToErp('true');


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
