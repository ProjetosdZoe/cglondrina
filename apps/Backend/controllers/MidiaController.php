<?php

namespace Backend\Controllers;

use Backend\Models\Audios         as Audios,
    Backend\Models\Videos         as Videos,
    Backend\Models\Albums         as Albums,
    Backend\Models\AlbumImages    as AlbumImages;

use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\File,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Hidden;

class MidiaController extends ControllerBase
{
    # CHECK IF: SESSION IS OK , IS POST , CSRF TOKEN ( Cross-Site Request Forgery )

    public function albumsAction()
    {
        
        $this->view->setVar('albums', Albums::find() );
        $this->view->setVar('csrf', $this->csrf->token);
    }
    
    public function adicionarAction()
    {
        
        $form = new Form();
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Testemunho",
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
            ]));
        
            $form->add(new Hidden("csrf" ,[
                'id'                    => "csrf",
                'value'                 => $this->session->get("CSRFToken")
            ]));
        
        $this->view->setVar('form', $form);
        
    }
    
    public function editarAction()
    {
        
        $testimony = Testimonies::findFirst($this->dispatcher->getParam(0));
        
        $form = new Form();
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Testemundo",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $testimony->title,
            ]));
        
            $form->add(new Text("author" ,[
                'placeholder'           => "Autor",
                'class'                 => "form-control",
                'id'                    => "author",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $testimony->author,
            ]));
        
            $form->add(new Textarea("text" ,[
                'placeholder'           => "Descrição",
                'class'                 => "form-control ckeditor",
                'id'                    => "text",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $testimony->text,
            ]));
        
            $form->add(new File("file" ,[
                'accept'    => "image/x-png, image/jpeg",
                'class'     => "form-control",
            ]));
        
            $form->add(new Hidden("csrf" ,[
                'id'                    => "csrf",
                'value'                 => $this->session->get("CSRFToken")
            ]));
        
        $this->view->setVar('form', $form);
        $this->view->setVar('csrf', $this->session->get("CSRFToken"));
        $this->view->setVar('images', TestimonyImages::findByPost($this->dispatcher->getParam(0)) );
        
    }
    
    public function criarAction()
    {
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->request->getPost('csrf') === $this->session->get("CSRFToken") ):

            $testimony = new Testimonies;
                if(Testimonies::findFirstByUrlrequest( $this->URLGenerator($this->request->getPost("title")) ))
                {
                    $testimony->urlrequest = $this->URLGenerator( $this->request->getPost("title").(new \DateTime())->format("-d-m-Y") );
                }
                else
                {
                    $testimony->urlrequest = $this->URLGenerator($this->request->getPost("title"));
                }
                $testimony->title      = $this->request->getPost("title");
                $testimony->text       = $this->request->getPost("text");
                $testimony->author     = $this->request->getPost("author");
                $testimony->date       = (new \DateTime("now"))->format("Y-m-d H:i:s");
                $testimony->views      = 0;
            $testimony->save();

            foreach($this->request->getUploadedFiles() as $file){
                $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/testimonies/{$filename}");

                $t_imgs = new TestimonyImages;
                    $t_imgs->post  = $testimony->_;
                    $t_imgs->image = $filename;
                $t_imgs->save();

            }

            $this->flashSession->success("<div class='title'>CADASTRADO COM SUCESSO !</div> <div class='msg'>Testemunho cadastrado com sucesso !</div> ");
            return $this->response->redirect("admin/testemunhos");

            
        else:
            return $this->response->redirect("admin/testemunhos");
        
        endif;
        
    }
    
    public function alterarAction()
    {
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->request->getPost('csrf') === $this->session->get("CSRFToken") ):
        
                $testimony = Testimonies::findFirst($this->dispatcher->getParam(0));
        
                    if(Testimonies::findFirstByUrlrequest( $this->URLGenerator($this->request->getPost("title")) ))
                    {
                        $testimony->urlrequest = $this->URLGenerator( $this->request->getPost("title").(new \DateTime())->format("-d-m-Y") );
                    }
                    else
                    {
                        $testimony->urlrequest = $this->URLGenerator($this->request->getPost("title"));
                    }
                        
                    if ($this->request->getUploadedFiles()[0]->getError() == 0)
                    {
                        foreach($this->request->getUploadedFiles() as $file){
                            $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                            $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/testimonies/{$filename}");

                            $t_imgs = new TestimonyImages;
                                $t_imgs->post  = $this->dispatcher->getParam(0);
                                $t_imgs->image = $filename;
                            $t_imgs->save();

                        }
                    }

                    $testimony->title      = $this->request->getPost("title");
                    $testimony->text       = $this->request->getPost("text");
                    $testimony->author     = $this->request->getPost("author");
                    $testimony->date       = (new \DateTime("now"))->format("Y-m-d H:i:s");
                    $testimony->views      = $testimony->views ;
                $testimony->update();

                $this->flashSession->success("<div class='title'>ALTERADO COM SUCESSO !</div> <div class='msg'>Testemunho alterado com sucesso !</div> ");
                return $this->response->redirect("admin/testemunhos");
        
            
        else:
            return $this->response->redirect("admin/testemunhos");
        
        endif;
        
    }
    
    public function removerAction()
    {
        if ( $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->dispatcher->getParam(1) === $this->session->get("CSRFToken") ):
        
            $testimony = Testimonies::findFirst( $this->dispatcher->getParam(0) );
            
                foreach(TestimonyComments::findByPost( $this->dispatcher->getParam(0) ) as $comment)
                {
                    $comment->delete();
                }
                foreach(TestimonyImages::findByPost( $this->dispatcher->getParam(0) ) as $t)
                {
                    unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/testimonies/{$t->image}");
                    $t->delete();
                }

                unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/testimonies/{$testimony->image}");

            $testimony->delete();

            $this->flashSession->success("<div class='title'>REMOVIDO COM SUCESSO !</div> <div class='msg'>Testemunho removido com sucesso !</div> ");
            return $this->response->redirect("admin/testemunhos");
        
        else:
            return $this->response->redirect("admin/testemunhos");
        
        endif;
    }
    
    public function rmiAction()
    {
        
        if ( $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->dispatcher->getParam(2) === $this->session->get("CSRFToken") ):
        
            $testimony = TestimonyImages::findFirst( $this->dispatcher->getParam(0) );

                unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/testimonies/{$testimony->image}");

            $testimony->delete();

            $this->flashSession->success("<div class='title'>REMOVIDO COM SUCESSO !</div> <div class='msg'>Imagem removida com sucesso !</div> ");
            return $this->response->redirect("admin/testemunhos/editar/{$this->dispatcher->getParam(1)}");
        
        else:
            return $this->response->redirect("admin/testemunhos/editar/{$this->dispatcher->getParam(1)}");
        
        endif;
        
    }

}