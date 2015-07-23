<?php

namespace Backend\Controllers;

use Backend\Models\Ministries as Ministries;

use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Hidden;

class MinisteriosController extends ControllerBase
{
    # CHECK IF: SESSION IS OK , IS POST , CSRF TOKEN ( Cross-Site Request Forgery )

    public function indexAction()
    {
        
        $ministries = Ministries::find();
        $this->view->setVar('ministries', $ministries);
        $this->view->setVar('csrf', $this->csrf->token);

    }
    
    public function adicionarAction()
    {
        
        $form = new Form();
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Evento",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));
        
            $form->add(new Textarea("text" ,[
                'placeholder'           => "Descrição",
                'class'                 => "form-control ckeditor",
                'id'                    => "text",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));
        
            $form->add(new Hidden("csrf" ,[
                'id'                    => "csrf",
                'value'                 => $this->session->get("CSRFToken")
            ]));
        
        $this->view->setVar('form', $form);
        
    }
    
    public function editarAction()
    {
        
        $ministry = Ministries::findFirst($this->dispatcher->getParam(0));
        
        $form = new Form();
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Evento",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $ministry->title,
            ]));
        
            $form->add(new Textarea("text" ,[
                'placeholder'           => "Descrição",
                'class'                 => "form-control ckeditor",
                'id'                    => "text",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $ministry->text,
            ]));
        
            $form->add(new Hidden("csrf" ,[
                'id'                    => "csrf",
                'value'                 => $this->session->get("CSRFToken")
            ]));
        
        $this->view->setVar('form', $form);
        
    }
    
    public function criarAction()
    {
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->request->getPost('csrf') === $this->session->get("CSRFToken") ):
            
            $ministry = new Ministries;
                if(Ministries::findFirstByUrlrequest( $this->URLGenerator($this->request->getPost("title")) ))
                {
                    $ministry->urlrequest = $this->URLGenerator( $this->request->getPost("title").(new \DateTime())->format("-d-m-Y") );
                }
                else
                {
                    $ministry->urlrequest = $this->URLGenerator($this->request->getPost("title"));
                }
                $ministry->title      = $this->request->getPost("title");
                $ministry->text       = $this->request->getPost("text");
            $ministry->save();

            $this->flashSession->success("<div class='title'>CADASTRADO COM SUCESSO !</div> <div class='msg'>Ministério cadastrada com sucesso !</div> ");
            return $this->response->redirect("admin/ministerios");
            
        else:
            return $this->response->redirect("admin/ministerios");
        
        endif;
        
    }
    
    public function alterarAction()
    {
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->request->getPost('csrf') === $this->session->get("CSRFToken") ):
            
                $ministry = Ministries::findFirst( $this->dispatcher->getParam(0) );
                    if(Ministries::findFirstByUrlrequest( $this->URLGenerator($this->request->getPost("title")) ))
                    {
                        $ministry->urlrequest = $this->URLGenerator( $this->request->getPost("title").(new \DateTime())->format("-d-m-Y") );
                    }
                    else
                    {
                        $ministry->urlrequest = $this->URLGenerator($this->request->getPost("title"));
                    }
                    $ministry->title      = $this->request->getPost("title");
                    $ministry->text       = $this->request->getPost("text");
                $ministry->update();

                $this->flashSession->success("<div class='title'>ALTERADO COM SUCESSO !</div> <div class='msg'>Ministério alterado com sucesso !</div> ");
                return $this->response->redirect("admin/ministerios");
            
        else:
            return $this->response->redirect("admin/ministerios");
        
        endif;
        
    }
    
    public function removerAction()
    {
        if ( $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->dispatcher->getParam(1) === $this->session->get("CSRFToken") ):
        
            Ministries::findFirst( $this->dispatcher->getParam(0) )->delete();

            $this->flashSession->success("<div class='title'>REMOVIDO COM SUCESSO !</div> <div class='msg'>Ministério removido com sucesso !</div> ");
            return $this->response->redirect("admin/ministerios");
        
        else:
            return $this->response->redirect("admin/ministerios");
        
        endif;
    }

}