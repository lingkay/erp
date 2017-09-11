<?php

namespace Gist\ContactBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ContactBundle\Entity\ContactPerson;
use Gist\ValidationException;

class ContactPersonController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'cnt_contact_person';
        $this->title = 'Address';

        $this->list_title = 'Addresses';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
        return new ContactPerson();
    }

    protected function update($o, $data, $is_new = false)
    {

        $o->setFirstName($data['first_name'])
            ->setMiddleName($data['middle_name'])
            ->setLastName($data['last_name'])
            ->setEmail($data['email_address']);

        //set as primary number
        if(isset($data['contact_person_is_primary']) && $data['contact_person_is_primary'] == 1)
        {
            $o->setIsPrimary();
        }

        // $em = $this->getDoctrine()->getManager();
        // $supplier = $em->getRepository('GistPurchasingBundle:Supplier')->find(17);

        // $o->setFirstName('first_name')
        //     ->setMiddleName('middle_name')
        //     ->setLastName('last_name')
        //     ->setEmail('email_address')
        //     ->setSupplier($supplier);



        //set as primary address
        // if(isset($data['is_primary']) && $data['is_primary'] == 1)
        //     $o->setIsPrimary();

        // $o->setUserCreate($this->getUser());
    }

    protected function getObjectLabel($object)
    {
        return $object->getFirstName();
    }

    protected function buildData($o)
    {
        $data = array(
            'id' => $o->getID(),
            'first_name' => $o->getFirstName(),
            'middle_name' => $o->getMiddleName(),
            'last_name' => $o->getLastName(),
            'email' => $o->getEmail(),
            'is_primary' => $o->getIsPrimary(),
        );

        return $data;
    }

    public function deleteContactPersonAction($id , $supp_id)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $this->get('gist_contact');
        $contact_person = $contact->getContactPerson($id);
        $em->remove($contact_person);
        $em->flush();

        $this->addFlash('success', 'Contact Person has been deleted successfully.');

        return $this->redirect($this->generateUrl('cat_pur_supp_edit_form', 
            array(
                    'id' => $supp_id,
                )));
    }



}
