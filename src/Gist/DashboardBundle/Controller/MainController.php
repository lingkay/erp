<?php

namespace Gist\DashboardBundle\Controller;

use Gist\TemplateBundle\Model\BaseController;

class MainController extends BaseController
{
    public function indexAction()
    {
        $this->title = 'Dashboard';
        
        $params = $this->getViewParams('', 'cat_dashboard_index');

        return $this->render('GistDashboardBundle:Main:index.html.twig', $params);
    }
}
