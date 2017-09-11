<?php

namespace Gist\LogBundle\Model;

use Gist\LogBundle\Entity\LogEntry;

class LogManager
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function log($action_id, $desc, $data, $type = "")
    {
        $em = $this->container->get('doctrine')->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        // $ser_data = serialize($data);

        $log = new LogEntry();
        $log->setUser($user)
            ->setActionID($action_id)
            ->setDescription($desc)
            ->setType($type)
            ->setData($data);

        $em->persist($log);
        $em->flush();
    }
}
