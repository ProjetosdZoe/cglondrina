<?php

namespace Frontend\Controllers;

use Frontend\Models\Newsletters as Newsletters;

class GcemController extends ControllerBase
{

    public function IndexAction()
    {
        
        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css');
        
    }
    
    public function BoletinsAction()
    {
        
        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css');
        
        $newsletters = Newsletters::find();
        
        $this->view->setVar("newsletters",$newsletters);
        
    }
    
    public function MapaAction()
    {
        
        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css')
             ->addJs('http://maps.googleapis.com/maps/api/js',false);
        
    }
    
    public function CtlAction()
    {
        
        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css');
        
    }

}

