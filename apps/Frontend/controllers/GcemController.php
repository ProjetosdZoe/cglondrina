<?php

namespace Frontend\Controllers;

use Frontend\Models\Newsletters as Newsletters,
    Frontend\Models\NewslettersAuthors as NewslettersAuthors,
    Frontend\Models\NewslettersCategories as NewslettersCategories;

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
        
        $newsletters = Newsletters::find([ 'order' => 'date' ]);
        $categories = NewslettersCategories::find();
        $authors = NewslettersAuthors::find([ 'order' => 'name' ]);
        
        $this->view->setVar("categories", $categories );
        
        if( $this->dispatcher->getParam(0) )
        {
            
            $this->view->setVar("authors", $authors );
            
        }
        
        if( $this->dispatcher->getParam(1) )
        {
            
            $category = NewslettersCategories::findFirstByUrlrequest($this->dispatcher->getParam(0));
            $authors = NewslettersAuthors::findFirstByUrlrequest($this->dispatcher->getParam(1));
            $this->view->setVar("newsletters", Newsletters::find([ "author = '{$authors->_}' and category ='{$category->_}' " ]) );
            
        }
        
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

