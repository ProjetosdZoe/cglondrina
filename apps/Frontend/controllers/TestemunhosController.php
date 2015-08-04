<?php

namespace Frontend\Controllers;

use Frontend\Models\Testimonies as Testimonies,
    Frontend\Models\TestimonyComments as TestimonyComments,
    Frontend\Models\TestimonyImages as TestimonyImages;

class TestemunhosController extends ControllerBase
{
    
    public function IndexAction()
    {
        
        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css');
        
        $testimonies = Testimonies::find([
            'order'  => 'date DESC',
            'limit'  =>  4 
        ]);
        
        $this->view->setVar("testimonies",$testimonies);
            
    }
    
    public function PostAction()
    {
        
        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css');
        
        $testimony  = Testimonies::findFirstByUrlrequest($this->dispatcher->getParam(0));
        $comments   = TestimonyComments::find([
            "post   =  '{$testimony->_}' ",
            "order" => "_ DESC"
        ]);
        
        $testimony->views = ($testimony->views + 1);
        $testimony->update();

        $this->view->setVar("testimony", $testimony);
        $this->view->setVar("comments", $comments);
        $this->view->setVar("images", TestimonyImages::findByPost($testimony->_) );
            
    }

}

