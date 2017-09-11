<?php

namespace Gist\ConfigurationBundle\Model;

use Doctrine\ORM\EntityManager;
use Gist\ConfigurationBundle\Entity\ConfigEntry;
use Symfony\Component\Yaml\Parser as YamlParser;

class ConfigurationManager
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function set($id, $value)
    {
        $em = $this->container->get('doctrine')->getManager();
        $entry = $em->getRepository('GistConfigurationBundle:ConfigEntry')->find($id);

        // if it's new
        if ($entry == null)
        {
            $entry = new ConfigEntry($id, $value);

            $em->persist($entry);

            return $this;
        }

        // if it's there already
        $entry->setValue($value);

        return $this;
    }

    public function get($id)
    {
        $em = $this->container->get('doctrine')->getManager();
        $entry = $em->getRepository('GistConfigurationBundle:ConfigEntry')->find($id);

        if ($entry == null)
            return null;

        return $entry->getValue();
    }

    public function getAll()
    {
        $em = $this->container->get('doctrine')->getManager();
        $entries = $em->getRepository('GistConfigurationBundle:ConfigEntry')->findAll();
        $ret = [];
        foreach ($entries as $entry) {
            $ret[$entry->getID()] = $entry->getValue();
        }
        return $ret;
    }

    public function getDisplayEntries()
    {
        $kernel = $this->container->get('kernel');
        $bundles = $this->container->getParameter('Gist_configuration.bundles');

        $disp = array();

        foreach ($bundles as $bundle)
        {
            try
            {
                $path = $kernel->locateResource('@' . $bundle . '/Resources/config/config.yml');
                $result = $this->parseConfigEntries($path);
                foreach ($result as $cat_name => $cat)
                {
                    // merge the arrays
                    if (!isset($disp[$cat_name]))
                        $disp[$cat_name] = new Category($cat_name);

                    foreach ($cat->getEntries() as $entry)
                        $disp[$cat_name]->addEntry($entry);
                }
            }
            catch (\InvalidArgumentException $e)
            {
                error_log($bundle . ' configuration not found.');
                continue;
            }
        }

        return $disp;
    }

    protected function parseDisplayEntry($data)
    {
        // display entries
        $disp_entry = new DisplayEntry($data['id']);
        $disp_entry->setLabel($data['label'])
            ->setType($data['type']);

        // value
        $value = $this->get($data['id']);
        $disp_entry->setValue($value);

        // parse by type
        switch ($data['type'])
        {
            case 'select':
                $method = $data['options_method'];
                $options = $this->container->get($data['options_service'])->$method();
                $disp_entry->setOptions($options);
                return $disp_entry;
             default:
                return $disp_entry;
        }

        return null;
    }

    public function parseConfigEntries($path)
    {
        $parser = new YamlParser();
        $config = $parser->parse(file_get_contents($path));
        $result = array();

        if (!isset($config['config']))
        {
            error_log('No Configuration entries for ' . $path);
            return;
        }

        foreach ($config['config'] as $data)
        {
            // get category
            $cat_name = $data['category'];
            if (isset($result[$cat_name]))
                $cat = $result[$cat_name];
            else
            {
                $cat = new Category($cat_name);
                $result[$cat_name] = $cat;
            }
            // parse entries
            foreach ($data['entries'] as $entry)
                $cat->addEntry($this->parseDisplayEntry($entry));
        }

        return $result;
    }
}
