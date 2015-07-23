<?php

namespace Frontend\Controllers;

class PedidosController extends ControllerBase
{

    public function indexAction()
    {
        
        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css');
        
    }

}

