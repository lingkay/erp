<?php

namespace Gist\POSERPBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\POSERPBundle\Entity\POSTransaction;
use Gist\POSERPBundle\Entity\POSTransactionItem;
use Gist\POSERPBundle\Entity\POSTransactionPayment;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gist\InventoryBundle\Entity\Transaction;
use Gist\InventoryBundle\Entity\Entry;
use Gist\InventoryBundle\Entity\Stock;

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

    public function saveTransactionAction($pos_loc_id, $id, $display_id, $total, $balance, $type, $customer_id, $status, $tax_rate, $orig_vat_amt, $new_vat_amt, $orig_amt_net_vat, $new_amt_net_vat, $tax_coverage, $cart_min, $orig_cart_total, $new_cart_total,$bulk_type,$transaction_mode,$transaction_cc_interest,$transaction_ea)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');

        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));
        //$pos_iacc_id = $pos_location->getInventoryAccount()->getDamagedContainer()->getID();


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
        

        // $transaction->setId($id);
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
        $transaction->setTransactionCCInterest($transaction_cc_interest);
        $transaction->setExtraAmount($transaction_ea);
        $transaction->setPOSLocation($pos_location);

        $em->persist($transaction);
        $em->flush();

        $list_opts[] = array('status'=>$transaction->getStatus(),'new_id'=>$transaction->getID());
        return new JsonResponse($list_opts);
    }

    public function saveTransactionItemsAction($trans_sys_id, $prod_id, $prod_name, $orig_price, $min_price, $adjusted_price, $discount_type, $discount_value, $isReturned, $isNew)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $transaction_item = new POSTransactionItem();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');

        $transaction = $em->getRepository('GistPOSERPBundle:POSTransaction')->findOneBy(array('trans_display_id'=>$trans_sys_id));
        $user = $em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>'1'));
        $pos_location = $transaction->getPOSLocation();
        
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

        //generate stock manipulation
        if (strtolower($transaction->getStatus()) == 'paid' and (strtolower($transaction->getTransactionMode()) != 'quotation')) {

            $prod = $em->getRepository('GistInventoryBundle:Product')->findOneBy(array('id' => $prod_id));
            if ($prod == null)
                throw new ValidationException('Could not find product.');

            if (strtolower($transaction->getTransactionMode()) == 'refund' || strtolower($transaction->getTransactionMode()) == 'exchange') {
                if ($isReturned == 'true') {
                    //returned item...add to POS
                    $dmg_acc = $pos_location->getInventoryAccount();
                    $adj_acc = $inv->findWarehouse($config->get('gist_adjustment_warehouse'));
                    $adj_acc = $adj_acc->getInventoryAccount();
                } else if ($isNew == 'true') {
                    //refund but new item...deduct from POS
                    $source = $inv->findWarehouse($config->get('gist_adjustment_warehouse'));
                    $dmg_acc = $source->getInventoryAccount();
                    $adj_acc = $pos_location->getInventoryAccount();
                } else {
                    //item no change
                    $list_opts[] = array('status'=>'ok');
                    return new JsonResponse($list_opts);
                }
            } else {
                $source = $inv->findWarehouse($config->get('gist_adjustment_warehouse'));
                $dmg_acc = $source->getInventoryAccount();
                $adj_acc = $pos_location->getInventoryAccount();
            }

            $new_qty = 1;
            $old_qty = 0;

            $remarks = 'POS Sales. TRANSACTION ID: ' . $transaction->getID();
            // setup transaction
            $trans = new Transaction();
            $trans->setUserCreate($user)
                ->setDescription($remarks);

            // add entries
            // entry for destination
            $wh_entry = new Entry();
            $wh_entry->setInventoryAccount($dmg_acc)
                ->setProduct($prod);

            // entry for source
            $adj_entry = new Entry();
            $adj_entry->setInventoryAccount($adj_acc)
                ->setProduct($prod);

            // check if debit or credit
            if ($new_qty > $old_qty) {
                $qty = $new_qty - $old_qty;
                $wh_entry->setDebit($qty);
                $adj_entry->setCredit($qty);
            } else {
                $qty = $old_qty - $new_qty;
                $wh_entry->setCredit($qty);
                $adj_entry->setDebit($qty);
            }
            $entries[] = $wh_entry;
            $entries[] = $adj_entry;

            foreach ($entries as $ent)
                $trans->addEntry($ent);

            $inv->persistTransaction($trans);
            $em->flush();
            //end stock manipulation
        }

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

    public function getUsersAction($area_id)
    {
        header("Access-Control-Allow-Origin: *");

        $search_array = array();
        $search_array['area'] = $area_id;

        
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository("GistUserBundle:User")->createQueryBuilder('o');
//        foreach ($search_array as $key => $value) {
//            if (trim($value) != '') {
//                if ($key == 'area') {
//                    $users->andWhere('IDENTITY(o.'.$key .') = :o_'.$key)
//                      ->setParameter('o_'.$key,''.$value.'');
//                }
//            }
//
//        }
        $results = $users->getQuery()->getResult();

        $list_opts = [];
        foreach ($results as $p) {
            $list_opts[] = array(
                'id'=>$p->getID(), 
                'username'=> ($p->getUsername() == null) ? '':$p->getUsername(),    
                'username_canonical'=> ($p->getUsernameCanonical() == null) ? '':$p->getUsernameCanonical(), 
                'salt'=> ($p->getSalt() == null) ? '':$p->getSalt(), 
                'position'=> ($p->getGroup()->getName() == null) ? '':$p->getGroup()->getName(), 
                'department'=> ($p->getGroup()->getDepartment()->getDepartmentName() == null) ? '':$p->getGroup()->getDepartment()->getDepartmentName(),
                'email'=> ($p->getEmail() == null) ? '':$p->getEmail(), 
                'password'=> ($p->getPassword() == null) ? '':$p->getPassword(), 
                'plainPassword'=> ($p->getPlainPassword() == null) ? '':$p->getPlainPassword(), 
                'confirmationToken'=> ($p->getConfirmationToken() == null) ? '':$p->getConfirmationToken(), 
                'enabled'=> ($p->isEnabled() == null) ? '':$p->isEnabled(), 
                'first_name'=> ($p->getFirstName() == null) ? '':$p->getFirstName(), 
                'middle_name'=> ($p->getMiddleName() == null) ? '':$p->getMiddleName(), 
                'last_name'=> ($p->getLastName() == null) ? '':$p->getLastName(), 
                'brand'=> ($p->getBrand()->getName() == null) ? '':$p->getBrand()->getName(), 
                'commission_type'=> ($p->getCommissionType() == null) ? '':$p->getCommissionType(), 
                'contact_number'=> ($p->getContactNumber() == null) ? '':$p->getContactNumber(),
            );
        }

        $admin_user = $em->getRepository('GistUserBundle:User')->findOneBy(array('username'=>'admin'));
        $list_opts[] = array(
            'id'=>$admin_user->getID(), 
            'username'=> ($admin_user->getUsername() == null) ? '':$admin_user->getUsername(),    
            'username_canonical'=> ($admin_user->getUsernameCanonical() == null) ? '':$admin_user->getUsernameCanonical(), 
            'salt'=> ($admin_user->getSalt() == null) ? '':$admin_user->getSalt(), 
            'position'=> ($admin_user->getGroup()->getName() == null) ? '':$admin_user->getGroup()->getName(), 
            'department'=> ($admin_user->getGroup()->getDepartment()->getDepartmentName() == null) ? '':$admin_user->getGroup()->getDepartment()->getDepartmentName(),
            'email'=> ($admin_user->getEmail() == null) ? '':$admin_user->getEmail(), 
            'password'=> ($admin_user->getPassword() == null) ? '':$admin_user->getPassword(), 
            'plainPassword'=> ($admin_user->getPlainPassword() == null) ? '':$admin_user->getPlainPassword(), 
            'confirmationToken'=> ($admin_user->getConfirmationToken() == null) ? '':$admin_user->getConfirmationToken(), 
            'enabled'=> ($admin_user->isEnabled() == null) ? '':$admin_user->isEnabled(), 
            'first_name'=> ($admin_user->getFirstName() == null) ? '':$admin_user->getFirstName(), 
            'middle_name'=> ($admin_user->getMiddleName() == null) ? '':$admin_user->getMiddleName(), 
            'last_name'=> ($admin_user->getLastName() == null) ? '':$admin_user->getLastName(), 
            'brand'=> 'null', 
            'commission_type'=> ($admin_user->getCommissionType() == null) ? '':$admin_user->getCommissionType(), 
            'contact_number'=> ($admin_user->getContactNumber() == null) ? '':$admin_user->getContactNumber(),
        );



        return new JsonResponse($list_opts);
    }

    public function getProductsAction($pos_loc_id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();

        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));
        $locations_brands = explode(',', $pos_location->getBrand());

        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository("GistInventoryBundle:Product")->createQueryBuilder('o');
        $results = $products->getQuery()->getResult();

        $list_opts = [];
        foreach ($results as $p) {
            if (in_array($p->getBrand()->getName(), $locations_brands)) {
                $list_opts[] = array(
                    'id'=>$p->getID(),
                    'item_code'=> $p->getItemCode(),
                    'bar_code'=> $p->getBarCode(),
                    'name'=> $p->getName(),
                    'brand'=> $p->getBrand()->getName(),
                );
            }
        }

        return new JsonResponse($list_opts);
    }
}
