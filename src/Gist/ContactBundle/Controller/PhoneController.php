<?php

namespace Gist\ContactBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ContactBundle\Entity\Phone;
use Gist\ValidationException;

class PhoneController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'cnt_phone';
        $this->title = 'Phone';

        $this->list_title = 'Phone';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
        return new Phone();
    }

    protected function update($o, $data, $is_new = false)
    {
        $cnt = $this->get('gist_contact');
        $o->setName($data['name'])
           ->setNumber($data['number']);

        //set as primary number
        if(isset($data['is_primary']) && $data['is_primary'] == 1)
            $o->setIsPrimary();

        $o->setUserCreate($this->getUser());
    }

    protected function getObjectLabel($object)
    {
        return $object->getName();
    }

    protected function buildData($o)
    {
        $data = array(
            'id' => $o->getID(),
            'name' => $o->getName(),
            'is_primary' => $o->getIsPrimary(),
            'number' => $o->getNumber()
        );

        return $data;
    }

    public function deletePhoneAction($id , $supp_id)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $this->get('gist_contact');
        $phone = $contact->getPhone($id);
        $em->remove($phone);
        $em->flush();

        $this->addFlash('success', 'Phone has been deleted successfully.');

        return $this->redirect($this->generateUrl('cat_pur_supp_edit_form', 
            array(
                    'id' => $supp_id,
                )));
    }

}
