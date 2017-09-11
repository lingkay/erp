<?php

namespace Gist\TemplateBundle\Model;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

abstract class BaseController extends Controller
{
    protected $title;

    protected function getURL($route_id, $alternate_url)
    {
        try
        {
            return $this->generateUrl($route_id);
        }
        catch (RouteNotFoundException $e)
        {
            return $alternate_url;
        }
    }

    protected function buildBreadcrumbTrail($current)
    {
        $layers = array();
        if ($current == null)
            return $layers;

        $layers[] = array(
            'label' => $current->getLabel(),
            'url' => $this->getURL($current->getID(), null)
        );
        while ($current->getParent() != null)
        {
            $current = $current->getParent();
            $layers[] = array(
                'label' => $current->getLabel(),
                'url' => $this->getURL($current->getID(), null)
            );
        }

        return array_reverse($layers);
    }

    protected function getMenu($selected = null, &$selected_obj)
    {
        // menu
        $menu_handler = $this->get('gist_menu');

        $menu_handler->setSelected($selected);

        $selected_obj = $menu_handler->getSelected();

        return $menu_handler->getMenu();
    }

    protected function addFlash($type, $message)
    {
        $this->get('session')->getFlashBag()->add($type, $message);
    }

    protected function getViewParams($subtitle = '', $selected = null)
    {
        $menu = $this->getMenu($selected, $sel_obj);
        $bcrumb = $this->buildBreadcrumbTrail($sel_obj);

        $conf = $this->get('gist_configuration');
        $media = $this->get('gist_media');
        if ($conf->get('hris_com_logo') != '') 
        {
            $path = $media->getUpload($conf->get('hris_com_logo'));

            // $str = $path->getURL();
            // $str = parse_url($str, PHP_URL_PATH);
            // $str = ltrim($str, '/');


            // echo $str;
            // exit;

            $logo_path = $path->getURL();
        }
        else
        {
            $logo_path = '';
        }

        $theme['primary'] = $conf->get('cat_color_primary') != null ? $conf->get('cat_color_primary'): "";
        $theme['secondary'] = $conf->get('cat_color_secondary') != null ? $conf->get('cat_color_secondary'): "";
        $theme['tertiary'] = $conf->get('cat_color_tertiary') != null ? $conf->get('cat_color_tertiary'): "";
        $theme['header'] = $conf->get('cat_color_header') != null ? $conf->get('cat_color_header'): "";

        // TODO: generate breadcrumb from menu
        return array(
            'left_menu' => $menu,
            'head_title' => $this->title,
            'head_subtitle' => $subtitle,
            'bcrumb' => $bcrumb,
            'dynamic_logo' => $logo_path,
            'theme' => $theme
        );
    }

    protected function checkAccess($acl_id)
    {
        $user = $this->getUser();
        if ($user->hasAccess($acl_id))
            return true;

        throw new AccessDeniedException();
    }

}
