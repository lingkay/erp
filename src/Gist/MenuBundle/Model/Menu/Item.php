<?php

namespace Gist\MenuBundle\Model\Menu;

class Item
{
    protected $id;
    protected $icon;
    protected $link;
    protected $label;
    protected $children;
    protected $selected;
    protected $parent;

    public function __construct()
    {
        $this->id = '';
        $this->icon = null;
        $this->link = null;
        $this->label = '';
        $this->children = array();
        $this->selected = false;
        $this->parent = null;
    }

    // setters
    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function setParent(self $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function addChild(self $child)
    {
        $child->setParent($this);

        // check if selected
        if ($child->isSelected())
            $this->setSelected();

        $this->children[] = $child;
        return $this;
    }

    public function setSelected($sel = true, $to_bubble = true)
    {
        if ($sel)
        {
            $this->selected = true;
            // bubble up to parents
            if ($this->parent != null && $to_bubble)
                $this->parent->setSelected(true, true);
        }
        else
            $this->selected = false;
        return $this;
    }

    // getters
    public function getID()
    {
        return $this->id;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function hasChildren()
    {
        if (count($this->children) > 0)
            return true;
        return false;
    }

    public function isSelected()
    {
        return $this->selected;
    }

    public function getParent()
    {
        return $this->parent;
    }
}
