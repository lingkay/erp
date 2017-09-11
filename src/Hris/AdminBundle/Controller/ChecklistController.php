<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\AdminBundle\Entity\Checklist;

class ChecklistController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_admin_checklist';
        $this->title = 'Pre-Employment Checklist';

        $this->list_title = 'Pre-Employment Checklist';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass() 
    {
        return new Checklist();
    }
    
    protected function update($o, $data,$is_new = false){
        $o->setName($data['name']);
        $o->setNotes($data['notes']);
        $this->updateTrackCreate($o, $data, $is_new);
    }

    protected function getObjectLabel($obj) 
    {
        if ($obj == null){
            return '';
        }
        return $obj->getName();
    }  

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Name', 'getName', 'name'),
            $grid->newColumn('Notes', 'getNotes', 'notes'),
        );
    }
}
