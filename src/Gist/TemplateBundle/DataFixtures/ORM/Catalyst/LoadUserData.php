<?php

namespace Gist\TemplateBundle\DataFixtures\ORM\Gist;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Gist\UserBundle\Entity\User;
use Gist\UserBundle\Model\UserManager;

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
                "id"=>1,
            ),
            "hr_lilys" => array(
                "password" => "12345",
                "email" => "hr@gist.com",
                "name" => "HR-Admin",
                "enabled" => true,
                "group" => "hr_admin",
                "id"=>2,
            )
        );

        foreach ($users as $username => $data) {
            $user = new User();
            $user->setUsername($username)
                ->setPlainPassword($data['password'])
                ->setEmail($data['email'])
                ->setName($data['name'])
                ->setEnabled($data['enabled'])
                ->setID($data['id']);

            if(isset($data['group'])) {
                $group = $em->getRepository('GistUserBundle:Group')
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