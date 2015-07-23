<?php

namespace Frontend\Controllers;

use Frontend\Models\Ministries  as Ministries;

class MinisteriosController extends ControllerBase
{

    public function indexAction()
    {
        
        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css');
        
        
        $ministries = Ministries::find([
            'order' => 'title'
        ]);

        $this->view->setVar("ministries",$ministries);
        
    }
    
    public function PostAction()
    {
        
        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css');
        
        $ministry = Ministries::findFirstByUrlrequest($this->dispatcher->getParam(0));

        $this->view->setVar("ministry",$ministry);
        
    }

}

