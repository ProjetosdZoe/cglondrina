<?php

namespace Backend\Controllers;

use Backend\Models\Users        as Users;

use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Password,
    Phalcon\Forms\Element\Hidden;

class SettingsController extends ControllerBase
{

    public function IndexAction()
    {
        
        $account = Users::findFirst( $this->session->get("secure_id") );
        $this->view->setVar("csrf" , $this->csrf->token );
        
        $form = new Form();
        
            $form->add(new Text("name" ,[
                'placeholder'           => "Nome Completo",
                'class'                 => "form-control",
                'id'                    => "name",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $account->name
            ]));
        
            $form->add(new Text("email" ,[
                'placeholder'           => "Endereço de E-Mail",
                'class'                 => "form-control",
                'id'                    => "email",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $account->email
            ]));
        
            $form->add(new Text("username" ,[
                'placeholder'           => "Usuário de Acesso",
                'class'                 => "form-control",
                'id'                    => "username",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $account->username
            ]));
        
            $form->add(new Password("password" ,[
                'placeholder'           => "Senha de Acesso",
                'class'                 => "form-control",
                'id'                    => "password",
            ]));
        
            $form->add(new Hidden("csrf" ,[
                'id'                    => "csrf",
                'value'                 => $this->session->get("CSRFToken")
            ]));
        
        $this->view->setVar('form', $form);
        
    }
    
    public function UpdateAction()
    {
        
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->request->getPost('csrf') === $this->session->get("CSRFToken") ):
        
            $account = Users::findFirst( $this->session->get("secure_id") );
                $account->name      = $this->request->getPost('name');
                $account->email     = $this->request->getPost('email');
                $account->username  = $this->request->getPost('username');
                
                if( $this->request->getPost('password') != "" )
                {
                    $account->password  =  password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
                }
        
            $account->update();

            $this->flashSession->success("<div class='title'>ALTERADO COM SUCESSO !</div> <div class='msg'>Informações alteradas com sucesso !</div> ");
            return $this->response->redirect("admin/settings");
            
        else:
            return $this->response->redirect("admin/settings");
        
        endif;        
        
    }

}