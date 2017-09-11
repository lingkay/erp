<?php

namespace Gist\MediaBundle\Model;

class StorageEngineFactory
{
    // storage engine factory
    public function getStorageEngine($config)
    {
        // TODO: check for valid config entries

        // check based on type
        switch ($config['type'])
        {
            case 'local_file':
                $base_dir = $config['folder'];
                $base_url = $config['base_url'];
                return new StorageEngine\LocalFile($base_dir, $base_url);
        }

        return null;
    }
}


