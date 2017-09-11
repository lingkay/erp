<?php

namespace Gist\ContactBundle\Template\Controller;

trait HasPhones
{
    protected function updateHasPhones($o, $data, $is_new)
    {
        $em = $this->getDoctrine()->getManager();
        $phones = array();

        // get all phones associated to object
        $contacts = $o->getPhones();

        if(isset($data['phone_id'])){
            foreach ($data['phone_id'] as  $index_id => $add_id)
            {
                // find phone object
                $phone = $em->getRepository('GistContactBundle:Phone')->find($add_id);
                // add phone to array for future use
                array_push($phones, $phone->getId());

                // if phone is new, associate new phone to object
                if (isset($data['is_new_phone'][$add_id]) && $data['is_new_phone'][$add_id] == "new")
                {
                    // TODO: return error if address is not found or ignore
                    $o->addPhone($phone);
                }
                // if not new, update any changes to existing phone numbers
                else
                {   
                    $phone->setNumber($data['number'][$index_id]);
                    $phone->setName($data['name'][$index_id]);
                }

                // if selected, set phone as primary
                if(isset($data['is_primary']) && $data['is_primary'] == $add_id)
                    $phone->setIsPrimary(true);
                // else unset primary
                else
                    $phone->setIsPrimary(false);
            }
        }

        // if contact is not associated to object, remove from database
        foreach ($contacts as $contact) {
            if(!in_array($contact->getId(), $phones)) {
                $o->removePhone($em->getRepository('GistContactBundle:Phone')->find($contact->getId()));
                $em->remove($em->getRepository('GistContactBundle:Phone')->find($contact->getId()));
            }
        }
    }
    
    protected function padFormPhoneType(&$params){
        $cnt = $this->get('gist_contact');
        $params['phone_type_opts'] = $cnt->getPhoneTypeOptions();
    }
}

