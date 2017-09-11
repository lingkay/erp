<?php

namespace Gist\MenuBundle\Model\Menu;

use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Yaml\Parser as YamlParser;

class Generator
{
    protected $index;
    protected $menu;
    protected $bundles;
    protected $router;
    protected $container;

    public function __construct($container)
    {
        $this->index = new Collection();
        $this->menu = new Collection();
        $this->bundles = $container->getParameter('Gist_menu.bundle_menu');
        $this->router = $container->get('router');
        $this->container = $container;
    }

    protected function getAllBundleMenus()
    {
        // get kernel for locateResource
        $kernel = $this->container->get('kernel');

        // loop through all bundles
        foreach ($this->bundles as $name)
        {
            try
            {
                // get location of menu.yml
                $path = $kernel->locateResource('@' . $name . '/Resources/config/menu.yml');

                // process bundle menu
                $this->parseBundleMenu($path);
            }
            catch (\InvalidArgumentException $e)
            {
                error_log($name . ' menu not found.');
                continue;
            }
        }
    }

    protected function parseBundleMenu($path)
    {
        $parser = new YamlParser();
        $menu_config = $parser->parse(file_get_contents($path));
        $user = $this->container->get('security.context')->getToken()->getUser();

        // check if we have menu items
        if (!isset($menu_config['menu_items']))
        {
            error_log('No menu_items found for ' . $path);
            return;
        }

        // go through each one
        foreach ($menu_config['menu_items'] as $mi_data)
        {
            // check params
            if (!isset($mi_data['icon']))
                $mi_data['icon'] = null;

            // acl
            if (isset($mi_data['acl']))
            {
                // if no access, skip
                if (!$user->hasAccess($mi_data['acl']))
                    continue;
            }

            // instantiate
            $mi = $this->newItem($mi_data['id'], $mi_data['label'], $mi_data['icon']);

            // check parent
            if (isset($mi_data['parent']) && $mi_data['parent'] != null)
            {
                $parent = $this->index->get($mi_data['parent']);
                if ($parent == null)
                    continue;

                $parent->addChild($mi);
            }
            else
                $this->menu->add($mi);

        }
    }

    protected function newItem($id, $label, $icon = null)
    {
        $mi = new Item();
        $mi->setID($id)
            ->setLabel($label);

        try
        {
            $mi->setLink($this->router->generate($id));
        }
        catch (RouteNotFoundException $e)
        {
            // no route, set to #
            $mi->setLink('#');
        }

        if ($icon != null)
            $mi->setIcon($icon);

        $this->index->add($mi);

        return $mi;
    }

    public function generate()
    {
        $this->getAllBundleMenus();

        return $this->menu;
    }

    public function getIndex()
    {
        return $this->index;
    }
}
