<?php

namespace Backend\Controllers;

use Backend\Models\Users as Users;

class LoginController extends ControllerBase
{

    public function IndexAction()
    {
        
    }
    
    public function AuthAction()
    {
        
        if ($this->request->isPost() == true):

            $username = preg_replace('/\s+/', '', $this->request->getPost("username"));
            $password = preg_replace('/\s+/', '', $this->request->getPost("password"));
            
            ( preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $username ) == false ) ? $user = Users::findFirstByUsername($username) : $user = Users::findFirstByEmail($username);
            
            if($user->_ != NULL && in_array( $user->permission , range(1,4)) === true ){
                
                if(password_verify( $password , $user->password ) == true){
                    
                    $this->session->set("secure_id", $user->_);
                    return $this->response->redirect("admin/index");
                    
                }
                else{
                    
                    $this->flashSession->error("<div class='title'>ERRO AO ACESSAR !</div> <div class='msg'> Senha Fornecida Inválida !</div> ");
                    return $this->response->redirect("admin/login");
                    
                }
                
            }
            else{
                
                $this->flashSession->error("<div class='title'>ERRO AO ACESSAR !</div> <div class='msg'>Você não tem permissão para acessar esta área ou seu nome de usuário está incorreto!</div> ");
                return $this->response->redirect("admin/login");
                
            }
            
        
        else:
            return $this->response->redirect("admin/login");
        endif;
        
        $this->view->disable();

    }
    
    public function logoutAction() 
    {
        if($this->request->isPost() == true):
            $this->session->destroy();
        else:
            return $this->response->redirect("admin/index");
        endif;

    }


}

