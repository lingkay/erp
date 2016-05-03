<?php

namespace Catalyst\TemplateBundle\DataFixtures\ORM\Catalyst;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Catalyst\UserBundle\Entity\User;
use Catalyst\UserBundle\Model\UserManager;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        $users = array(
            "admin" => array(
                "password" => "admin",
                "email" => "test@test.com",
                "name" => "Administrator",
                "enabled" => true,
            ),
            "hr_lilys" => array(
                "password" => "12345",
                "email" => "hr@mylilys.com",
                "name" => "HR-Admin",
                "enabled" => true,
                "group" => "hr_admin",
            )
        );

        foreach ($users as $username => $data) {
            $user = new User();
            $user->setUsername($username)
                ->setPlainPassword($data['password'])
                ->setEmail($data['email'])
                ->setName($data['name'])
                ->setEnabled($data['enabled']);

            if(isset($data['group'])) {
                $group = $em->getRepository('CatalystUserBundle:Group')
                    ->findBy(array("name" => $data['group']));
                if ($group != null) {
                    foreach ($group as $grp) {
                        $user->addGroup($grp);
                    }
                } 
            }
        
            $em->persist($user);

            if($username == 'admin')
                $this->addReference('admin', $user);
        }

        $em->flush();
    }
    
    public function getOrder()
    {
        return 1;
    }
}