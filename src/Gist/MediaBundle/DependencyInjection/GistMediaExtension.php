<?php

namespace Gist\MediaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Gist\MediaBundle\Model\StorageEngine;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;

class GistMediaExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        // storage config
     
        $container->setParameter('gist_media.storage_config', array_merge($config[0]['upload_storage'],$config[1]['upload_storage']));

        // load services.yml
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
