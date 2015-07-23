<?php

namespace Backend\Controllers;

use Backend\Models\Users        as Users,
    Backend\Models\Newsletter   as Newsletter;

class IndexController extends ControllerBase
{

    public function IndexAction()
    {
        
        $newsletters = Newsletter::find([ 
            'order' => 'date DESC'
        ]);
        
        $this->view->setVar("newsletters",$newsletters);
        $this->view->setVar("csrf" , $this->csrf->token );
        
    }
    
    
    public function AuthAction()
    {
        
        if ($this->session->has("secure_id")):
            return $this->response->redirect("admin/index");
        
        else:
            return $this->response->redirect("admin/login");
        
        endif;
        
    }
    
    
    public function RemoverAction()
    {
        
        if ( $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->dispatcher->getParam(1) === $this->session->get("CSRFToken") ):
        
            Newsletter::findFirst( $this->dispatcher->getParam(0) )->delete();

            $this->flashSession->success("<div class='title'>REMOVIDO COM SUCESSO !</div> <div class='msg'>Assinatura removida com sucesso !</div> ");
            return $this->response->redirect("admin/index");
        
        else:
            return $this->response->redirect("admin/index");
        
        endif;
        
        
    }

}