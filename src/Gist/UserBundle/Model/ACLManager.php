<?php

namespace Gist\UserBundle\Model;

use Symfony\Component\Yaml\Parser as YamlParser;

class ACLManager
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getAllACLEntries()
    {
        $kernel = $this->container->get('kernel');
        $bundles = $this->container->getParameter('gist_user.acl_entries');
        $acl_entries = array();

        foreach ($bundles as $bundle)
        {
            try
            {
                $path = $kernel->locateResource('@' . $bundle['id'] . '/Resources/config/acl.yml');

                $acl_entries[$bundle['label']] = $this->parseACLEntries($path);

            }
            catch (\InvalidArgumentException $e)
            {
                error_log($bundle['id'] . ' ACL not found.');
                error_log($e->getMessage());
                continue;
            }
        }

        return $acl_entries;
    }

    public function parseACLEntries($path)
    {
        $parser = new YamlParser();
        $config = $parser->parse(file_get_contents($path));
        $result = array();

        // error_log(print_r($config, true));

        // acl entries
        if (!isset($config['acl']))
        {
            error_log('No ACL entries found for ' . $path);
            return;
        }

        // acl entries
        foreach ($config['acl'] as $data)
        {
            $category = $data['category'];
            $acl_entries = $data['entries'];
            $entries = array();
            foreach ($acl_entries as $entry)
                $entries[$entry['id']] = $entry['label'];

            $result[$category] = $entries;
        }

        return $result;
    }
}
