<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Hris\AdminBundle\Entity\Downloadables;
use Gist\CoreBundle\Template\Controller\TrackCreate;

class DownloadablesController extends CrudController
{
    use TrackCreate;
    
    public function __construct()
    {
        $this->route_prefix = 'hris_admin_downloadables';
        $this->title = 'Downloadable Forms';

        $this->list_title = 'Downloadable Forms';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass() 
    {
        return new Downloadables();
    }
    
    protected function update($o,$data,$is_new = false)
    {
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();

        $media = $this->get('gist_media');

        $o->setName($data['name']);
        $o->setNotes($data['notes']);

        if($data['file']!=0 && $data['file'] != ""){
            $o->setUpload($media->getUpload($data['file']));
        }

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
            $grid->newColumn('File Name', 'getName', 'name'),
            $grid->newColumn('Details/Description', 'getNotes', 'notes'),
        );
    }
}
