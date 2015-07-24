<?php

namespace Backend\Controllers;

use Backend\Models\Testimonies         as Testimonies,
    Backend\Models\TestimonyImages     as TestimonyImages,
    Backend\Models\TestimonyComments   as TestimonyComments;

use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\File,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Hidden;

class TestemunhosController extends ControllerBase
{
    # CHECK IF: SESSION IS OK , IS POST , CSRF TOKEN ( Cross-Site Request Forgery )

    public function indexAction()
    {
        
        $this->view->setVar('testimonies', Testimonies::find() );
        $this->view->setVar('csrf', $this->csrf->token);
    }
    
    public function adicionarAction()
    {
        
        $form = new Form();
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Testemundo",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));
        
            $form->add(new Text("author" ,[
                'placeholder'           => "Autor",
                'class'                 => "form-control",
                'id'                    => "author",
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
        
            $form->add(new File("file" ,[
                'accept'    => "image/x-png, image/jpeg",
                'class'     => "form-control",
                'id'        => "file",
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
        
        $testemony = Testemonies::findFirst($this->dispatcher->getParam(0));
        
        $form = new Form();
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Testemundo",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $testemony->title,
            ]));
        
            $form->add(new Text("author" ,[
                'placeholder'           => "Autor",
                'class'                 => "form-control",
                'id'                    => "author",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $testemony->author,
            ]));
        
            $form->add(new Textarea("text" ,[
                'placeholder'           => "Descrição",
                'class'                 => "form-control ckeditor",
                'id'                    => "text",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $testemony->text,
            ]));
        
            $form->add(new File("file" ,[
                'accept'    => "image/x-png, image/jpeg",
                'class'     => "form-control",
                'id'        => "file",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
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
        
            switch($this->request->getUploadedFiles()[0]->getError()){
                case 1 : $e_message = "O arquivo enviado é muito grande!";  break;
                case 2 : $e_message = "O arquivo enviado é muito grande!";  break;
                case 3 : $e_message = "O arquivo não foi enviado corretamente!";  break;
                case 4 : $e_message = "Nenhum arquivo foi enviado!";  break;
            }
            
            if( $this->request->getUploadedFiles()[0]->getError() != 0 ){
                
                $this->flashSession->error("<div class='title'>ERRO AO CADASTRAR !</div> <div class='msg'>{$e_message}</div> ");
                return $this->response->redirect("admin/artigos/adicionar");
                
            }
            else{
                
                foreach($this->request->getUploadedFiles() as $file){
                    $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                    $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/articles/{$filename}");
                }
            
                $article = new Articles;
                    if(Articles::findFirstByUrlrequest( $this->URLGenerator($this->request->getPost("title")) ))
                    {
                        $article->urlrequest = $this->URLGenerator( $this->request->getPost("title").(new \DateTime())->format("-d-m-Y") );
                    }
                    else
                    {
                        $article->urlrequest = $this->URLGenerator($this->request->getPost("title"));
                    }
                    $article->title      = $this->request->getPost("title");
                    $article->text       = $this->request->getPost("text");
                    $article->date       = (new \DateTime("now"))->format("Y-m-d H:i:s");
                    $article->image      = $filename;
                    $article->views      = 0;
                    $article->category   = $this->request->getPost("category");
                $article->save();

                $this->flashSession->success("<div class='title'>CADASTRADO COM SUCESSO !</div> <div class='msg'>Artigo cadastrado com sucesso !</div> ");
                return $this->response->redirect("admin/artigos");
                
            }
            
        else:
            return $this->response->redirect("admin/artigos");
        
        endif;
        
    }
    
    public function alterarAction()
    {
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->request->getPost('csrf') === $this->session->get("CSRFToken") ):
        
                $article = Articles::findFirst($this->dispatcher->getParam(0));
        
                    if(Articles::findFirstByUrlrequest( $this->URLGenerator($this->request->getPost("title")) ))
                    {
                        $article->urlrequest = $this->URLGenerator( $this->request->getPost("title").(new \DateTime())->format("-d-m-Y") );
                    }
                    else
                    {
                        $article->urlrequest = $this->URLGenerator($this->request->getPost("title"));
                    }
                        
                    if ($this->request->getUploadedFiles()[0]->getError() == 0)
                    {
                        unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/articles/{$article->image}");

                        foreach ($this->request->getUploadedFiles() as $file){
                            $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                            $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/articles/{$filename}");
                        }

                        $article->image = $filename;
                    }
                    else
                    {
                        $article->image = $article->image;
                    }

                    $article->title      = $this->request->getPost("title");
                    $article->text       = $this->request->getPost("text");
                    $article->date       = (new \DateTime("now"))->format("Y-m-d H:i:s");
                    $article->views      = $article->views;
                    $article->category   = $this->request->getPost("category");
                $article->update();

                $this->flashSession->success("<div class='title'>ALTERADO COM SUCESSO !</div> <div class='msg'>Artigo alterado com sucesso !</div> ");
                return $this->response->redirect("admin/artigos");
        
            
        else:
            return $this->response->redirect("admin/artigos");
        
        endif;
        
    }
    
    public function removerAction()
    {
        if ( $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->dispatcher->getParam(1) === $this->session->get("CSRFToken") ):
        
            $article = Articles::findFirst( $this->dispatcher->getParam(0) );
            
                foreach(ArticleComments::findByPost( $this->dispatcher->getParam(0) ) as $comment)
                {
                    $comment->delete();
                }

                unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/articles/{$article->image}");

            $article->delete();

            $this->flashSession->success("<div class='title'>REMOVIDO COM SUCESSO !</div> <div class='msg'>Artigo removido com sucesso !</div> ");
            return $this->response->redirect("admin/artigos");
        
        else:
            return $this->response->redirect("admin/artigos");
        
        endif;
    }

}