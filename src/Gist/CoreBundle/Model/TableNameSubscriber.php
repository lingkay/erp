<?php

namespace Gist\CoreBundle\Model;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/* 
 * handles the auto-naming of many to many associations so table prefixes
 * will be added automatically
 */
class TableNameSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'loadClassMetadata'
        );
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $meta = $args->getClassMetadata();

        // sk
        if ($meta->isInheritanceTypeSingleTable() && !$meta->isRootEntity())
            return;

        $tname = $meta->getTableName();
        // error_log('table - ' . $tname);

        // get table prefix
        $tn_explode = explode('_', $tname);
        $prefix = $tn_explode[0];

        foreach ($meta->getAssociationMappings() as $fname => $mapping)
        {
            // auto modify the join table for many to many
            // we add the bundle prefix of the owning entity
            if ($mapping['type'] == ClassMetadataInfo::MANY_TO_MANY)
            {
                // check if we're the ownings side
                if (!isset($meta->associationMappings[$fname]['joinTable']['name']))
                    return;

                // error_log(print_r($mapping, true));
                $jt_name = $meta->associationMappings[$fname]['joinTable']['name'];

                // error_log('join table - ' . $jt_name);
                
                // check if prefix is already there
                if (strncmp($jt_name, $prefix . '_', strlen($prefix) + 1) === 0)
                    return;

                // add prefix
                $meta->associationMappings[$fname]['joinTable']['name'] = $prefix . '_' . $jt_name;
                // error_log('modified join table - ' . $prefix . '_' . $jt_name);
            }
        }
    }
}
