<?php

namespace Gist\ContactBundle\Template\Controller;

trait HasAddresses
{
    protected function updateHasAddresses($o, $data, $is_new)
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($data['address_id']))
        {
            foreach ($data['address_id'] as $index_id => $add_id)
            {
                $address = $em->getRepository('GistContactBundle:Address')->find($data['address_id'][$index_id]);

                if ($data['is_new_address'][$index_id] == "new")
                {
                    // TODO: return error if address is not found or ignore
                    $o->addAddress($address);
                }
                else
                {                    
                    $address->setName($data['name'][$index_id])
                        ->setStreet($data['street'][$index_id])
                        ->setCity($data['city'][$index_id])
                        ->setState($data['state'][$index_id])
                        ->setCountry($data['country'][$index_id])
                        ->setLongitude($data['longitude'][$index_id])
                        ->setLatitude($data['latitude'][$index_id]);
                }
            }
        }
    }
}

