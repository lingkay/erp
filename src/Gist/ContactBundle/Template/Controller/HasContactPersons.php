<?php

namespace Gist\ContactBundle\Template\Controller;

trait HasContactPersons
{
    protected function updateHasContactPersons($o, $data, $is_new)
    {

        // for contact person update
        $em = $this->getDoctrine()->getManager();
        if(isset($data['person_id']))
        {
            foreach ($data['person_id'] as $index_id => $person_id)
            {
                $contact_person = $em->getRepository('GistContactBundle:ContactPerson')->find($data['person_id'][$index_id]);

                if ($data['is_person_new'][$index_id] == "new")
                {
                    $o->addContactPerson($contact_person);
                }
                else
                {                    
                    // $contact_person->setNumber($data['p_number'][$index_id]);
                    //set as primary number
                    // if(isset($data['contact_person_is_primary'][$index_id]))
                    // {
                    //     $contact_person->setIsPrimary();
                    // }
                    // else
                    // {
                    //     $contact_person->setIsPrimary(false);
                    // }
                }
            }
        }

        // for contact numbers update
        if(isset($data['phone_id']))
        {
            foreach ($data['phone_id'] as $index_id => $person_id)
            {
                $contact_person = $em->getRepository('GistContactBundle:ContactPerson')->find($data['contact_person_id'][$index_id]);
                $phone = $em->getRepository('GistContactBundle:Phone')->find($data['phone_id'][$index_id]);

                if ($data['is_new_phone'][$index_id] == "new")
                {
                    $contact_person->addPhone($phone);
                }
                else
                {                    
                    $phone->setNumber($data['p_number'][$index_id]);

                    //set as primary number
                    // if(isset($data['phone_is_primary'][$index_id]))
                    // {
                    //     $phone->setIsPrimary();
                    // }
                    // else
                    // {
                    //     $phone->setIsPrimary(false);
                    // }

                }
            }
        }
    }
}

