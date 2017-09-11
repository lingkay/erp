<?php

namespace Gist\GridBundle\Model;

use Doctrine\ORM\EntityManager;

class Factory
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function newColumn(
        $label,
        $get_method,
        $order_field,
        $object_alias = 'o',
        callable $callback = null,
        $sortable = true,
        $searchable = true
    )
    {
        $col = new Grid\Column($label, $get_method, $order_field, $object_alias);
        if ($callback != null)
            $col->setFormatCallback($callback);
        $col->setSortable($sortable);
        $col->setSearchable($searchable);

        return $col;
    }

    public function newLoader()
    {
        return new Grid\Loader($this->em);
    }

    public function newJoin($alias, $field, $getter,$type = 'inner')
    {
        return new Grid\JoinRepo($alias, $field, $getter, $type);
    }

    public function newFilterGroup()
    {
        return new FilterGroup();
    }
}
