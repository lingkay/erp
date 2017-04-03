<?php

namespace Gist\ContactBundle\Template\Controller;

trait HasContactInfo
{
    use HasAddresses;
    use HasPhones;

    protected function updateContact($o, $data, $is_new)
    {
        $cnt = $this->get('gist_contact');
        $this->updateHasAddresses($o, $data, $is_new);
        $this->updateHasPhones($o, $data, $is_new);


        $o->setFirstName($data['first_name'])
            ->setEmail($data['email'])
            ->setLastName($data['last_name'])
            ->setMiddleName($data['middle_name'])
            ->setSalutation(isset($data['salutation'])?$data['salutation']:"");
    }


    protected function padFormContactInfo(&$params)
    {
        $cnt = $this->get('gist_contact');
        $this->padFormPhoneType($params);
        
        $params['contact_opts'] = $cnt->getContactTypeOptions();
    }
}

