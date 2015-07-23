<?php

namespace Backend\Controllers;

use Backend\Models\Banners as Banners;

use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\File,
    Phalcon\Forms\Element\Hidden;

class BannersController extends ControllerBase
{
    # CHECK IF: SESSION IS OK , IS POST , CSRF TOKEN ( Cross-Site Request Forgery )

    public function indexAction()
    {
        
        $banners = Banners::find();
        $this->view->setVar('banners', $banners);
        $this->view->setVar('csrf', $this->csrf->token);

    }
    
    public function adicionarAction()
    {
        
        $form = new Form();
        
            $form->add(new Text("phrase_one" ,[
                'placeholder'           =>  "Primeira Frase do Banner",
                'class'                 => "form-control",
                'id'                    => "phrase_one",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));

            $form->add(new Text("phrase_two" ,[
                'placeholder'           =>  "Segunda Frase do Banner",
                'class'                 => "form-control",
                'id'                    => "phrase_two",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));

            $form->add(new Text("phrase_three" ,[
                'placeholder'           =>  "Terceira Frase do Banner",
                'class'                 => "form-control",
                'id'                    => "phrase_three",
            ]));

            $form->add(new Text("phrase_four" ,[
                'placeholder'           =>  "Quarta Frase do Banner",
                'class'                 => "form-control",
                'id'                    => "phrase_four",
            ]));
        
            $form->add(new Hidden("csrf" ,[
                'id'                    => "csrf",
                'value'                 => $this->session->get("CSRFToken")
            ]));

            $form->add(new File("file" ,[
                'accept'    => "image/x-png, image/jpeg",
                'class'     => "form-control",
                'id'        => "file",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));
        
        $this->view->setVar('form', $form);
        
    }
    
    public function editarAction()
    {
        $banner = Banners::findFirstBy_($this->dispatcher->getParam(0));
        
        $form = new Form();
        
            $form->add(new Text("phrase_one" ,[
                'placeholder'           =>  "Primeira Frase do Banner",
                'class'                 => "form-control",
                'id'                    => "phrase_one",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $banner->phrase_one
            ]));

            $form->add(new Text("phrase_two" ,[
                'placeholder'           =>  "Segunda Frase do Banner",
                'class'                 => "form-control",
                'id'                    => "phrase_two",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $banner->phrase_two
            ]));

            $form->add(new Text("phrase_three" ,[
                'placeholder'           =>  "Terceira Frase do Banner",
                'class'                 => "form-control",
                'id'                    => "phrase_three",
                'value'                 => $banner->phrase_three
            ]));

            $form->add(new Text("phrase_four" ,[
                'placeholder'           =>  "Quarta Frase do Banner",
                'class'                 => "form-control",
                'id'                    => "phrase_four",
                'value'                 => $banner->phrase_four
            ]));
        
            $form->add(new Hidden("csrf" ,[
                'id'                    => "csrf",
                'value'                 => $this->session->get("CSRFToken")
            ]));

            $form->add(new File("file" ,[
                'accept'    => "image/x-png, image/jpeg",
                'class'     => "form-control",
                'id'        => "file"
            ]));
        
        $this->view->setVar('form', $form);
    }
    
    public function criarAction()
    {
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->request->getPost('csrf') === $this->session->get("CSRFToken") ):
            
            switch($this->request->getUploadedFiles()[0]->getError()){
                case 1 : $e_message = "O arquivo enviado é muito grande!";  break;
                case 2 : $e_message = "O arquivo enviado é muito grande!";  break;
                case 3 : $e_message = "O arquivo não foi enviado corretamente!";  break;
                case 4 : $e_message = "Nenhum arquivo foi enviado!";  break;
            }
            
            if( $this->request->getUploadedFiles()[0]->getError() != 0 ){
                
                $this->flashSession->error("<div class='title'>ERRO AO CADASTRAR !</div> <div class='msg'>{$e_message}</div> ");
                return $this->response->redirect("admin/banners/adicionar");
                
            }
            else{
                
                foreach($this->request->getUploadedFiles() as $file){
                    $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                    $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/big-slider/{$filename}");
                }
                
                $banner = new Banners;
                    $banner->image        = $filename;
                    $banner->phrase_one   = $this->request->getPost("phrase_one");
                    $banner->phrase_two   = $this->request->getPost("phrase_two");
                    $banner->phrase_three = $this->request->getPost("phrase_three");
                    $banner->phrase_four  = $this->request->getPost("phrase_four");
                $banner->save();

                $this->flashSession->success("<div class='title'>CADASTRADO COM SUCESSO !</div> <div class='msg'>Banner cadastrado com sucesso !</div> ");
                return $this->response->redirect("admin/banners");
            }
            
        else:
            return $this->response->redirect("admin/banners");
        
        endif;
        
    }
    
    public function alterarAction()
    {
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->request->getPost('csrf') === $this->session->get("CSRFToken") ):
            
                $banner = Banners::findFirst($this->dispatcher->getParam(0));
                    $banner->phrase_one   = $this->request->getPost("phrase_one");
                    $banner->phrase_two   = $this->request->getPost("phrase_two");
                    $banner->phrase_three = $this->request->getPost("phrase_three");
                    $banner->phrase_four  = $this->request->getPost("phrase_four");
                        
                    if ($this->request->getUploadedFiles()[0]->getError() == 0)
                    {
                        unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/big-slider/{$banner->image}");

                        foreach ($this->request->getUploadedFiles() as $file){
                            $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                            $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/big-slider/{$filename}");
                        }

                        $banner->image = $filename;
                    }
                    else
                    {
                        $banner->image = $banner->image;
                    }

                $banner->update();

                $this->flashSession->success("<div class='title'>ALTERADO COM SUCESSO !</div> <div class='msg'>Banner alterado com sucesso !</div> ");
                return $this->response->redirect("admin/banners");
            
        else:
            return $this->response->redirect("admin/banners");
        
        endif;
        
    }
    
    public function removerAction()
    {
        if ( $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->dispatcher->getParam(1) === $this->session->get("CSRFToken") ):
        
            $banner = Banners::findFirst( $this->dispatcher->getParam(0) );

                unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/big-slider/{$banner->image}");

            $banner->delete();

            $this->flashSession->success("<div class='title'>REMOVIDO COM SUCESSO !</div> <div class='msg'>Banner removido com sucesso !</div> ");
            return $this->response->redirect("admin/banners");
        
        else:
            return $this->response->redirect("admin/banners");
        
        endif;
    }

}