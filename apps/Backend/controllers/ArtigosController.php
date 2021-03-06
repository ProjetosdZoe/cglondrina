<?php

namespace Backend\Controllers;

use Backend\Models\Articles            as Articles,
    Backend\Models\ArticleComments     as ArticleComments,
    Backend\Models\ArticleCategories   as ArticleCategories;

use Mustache_Engine as Mustache;

use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\File,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Hidden;

class ArtigosController extends ControllerBase
{
    # CHECK IF: SESSION IS OK , IS POST , CSRF TOKEN ( Cross-Site Request Forgery )

    public function indexAction()
    {
        $articles = $this->modelsManager->executeQuery("
        SELECT
        Backend\Models\Articles._,
        Backend\Models\Articles.urlrequest,
        Backend\Models\Articles.title,
        Backend\Models\Articles.text,
        Backend\Models\Articles.date,
        Backend\Models\Articles.image,
        Backend\Models\ArticleCategories.name as category_name

        FROM Backend\Models\Articles
        LEFT JOIN Backend\Models\ArticleCategories ON Backend\Models\Articles.category = Backend\Models\ArticleCategories._
        ORDER BY Backend\Models\Articles._ DESC
        ");
        $this->view->setVar('articles', $articles);
        $this->view->setVar('categories', ArticleCategories::find() );
        $this->view->setVar('csrf', $this->csrf->token);
    }
    
    public function adicionarAction()
    {
        $this->assets
             ->addCss( $this->template->BJS . "select2/select2.css")
             ->addCss( $this->template->BJS . "select2/select2-bootstrap.css")
             ->addJs(  $this->template->BJS . "select2/select2.min.js");
        
        $form = new Form();
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Artigo",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));
        
            $form->add(new Select("category", ArticleCategories::find(), [
                'id'    => "category",
                'class' => "form-control",
                'using' => ['_', 'name'],
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
        $this->assets
             ->addCss( $this->template->BJS . "select2/select2.css")
             ->addCss( $this->template->BJS . "select2/select2-bootstrap.css")
             ->addJs(  $this->template->BJS . "select2/select2.min.js");
        
        $article = Articles::findFirst($this->dispatcher->getParam(0));
        
        $form = new Form();
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Artigo",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $article->title,
            ]));
        
            $form->add(new Select("category", ArticleCategories::find(), [
                'id'                    => "category",
                'class'                 => "form-control",
                'using'                 => ['_', 'name'],
                'value'     => $article->category
            ]));
        
            $form->add(new Textarea("text" ,[
                'placeholder'           => "Descrição",
                'class'                 => "form-control ckeditor",
                'id'                    => "text",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $article->text,
            ]));
        
            $form->add(new File("file" ,[
                'accept'    => "image/x-png, image/jpeg",
                'class'     => "form-control",
                'id'        => "file",
            ]));
        
            $form->add(new Hidden("csrf" ,[
                'id'                    => "csrf",
                'value'                 => $this->session->get("CSRFToken")
            ]));
        
        $this->view->setVar('form', $form);
        $this->view->setVar('image', $article->image);
        
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
    
    public function nwcAction()
    {
        
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->request->getPost('csrf') === $this->session->get("CSRFToken") ):
        
            $category = new ArticleCategories;
        
                if(ArticleCategories::findFirstByUrlrequest( $this->URLGenerator($this->request->getPost("title")) ))
                {
                    $category->urlrequest = $this->URLGenerator( $this->request->getPost("title").(new \DateTime())->format("-d-m-Y") );
                }
                else
                {
                    $category->urlrequest = $this->URLGenerator($this->request->getPost("title"));
                }

                $category->name   = $this->request->getPost("title");
            $category->save();

            $this->flashSession->success("<div class='title'>CADASTRADO COM SUCESSO !</div> <div class='msg'>Categoria cadastrada com sucesso !</div> ");
            return $this->response->redirect("admin/artigos");
        
        else:
            return $this->response->redirect("admin/artigos");
        
        endif;
        
    }
  
    # MODAL
    
    public function ModalAction()
    {
        
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") ): 
        
            $category = ArticleCategories::findFirst($this->request->getPost('id'));

            $form = new Form();
        
            if( $this->dispatcher->getParam(1) === "remove" )
            {
                
                if( $this->dispatcher->getParam(0) === "category" )
                {
                    $form->add(new Select("select", ArticleCategories::find(), [
                        'id'    => "select",
                        'class' => "form-control",
                        'using' => ['_', 'name'],
                    ]));
                    
                    $txt = "Escolha uma categoria para que quais quer artigos cadastradas na categoria de <b><u>{$category->name}</u></b> sejam alteradas.";
                    $isremove = true;

                }
                
            }
            if( $this->dispatcher->getParam(1) === "modify" )
            {
             
                if( $this->dispatcher->getParam(0) === "category" )
                {
                    
                    $form->add(new Text("name" ,[
                        'placeholder'           => "Título da Categoria",
                        'class'                 => "form-control",
                        'id'                    => "name",
                        'data-validate'         => "required",
                        'data-message-required' => "* Este campo é obrigatório.",
                        'value'                 => $category->name
                    ]));                    
                    
                    $txt = "Escolha um novo título para a categoria : <b><u>{$category->name}</u></b>.";
                    $isremove = false;

                }
                
            }
        
            $form->add(new Hidden("action" ,[
                'id'                    => "action",
                'value'                 => $this->dispatcher->getParam(1)
            ]));
        
            $form->add(new Hidden("type" ,[
                'id'                    => "type",
                'value'                 => $this->dispatcher->getParam(0)
            ]));
        
            $form->add(new Hidden("csrf" ,[
                'id'                    => "csrf",
                'value'                 => $this->session->get("CSRFToken")
            ]));
        
            $form->add(new Hidden("id" ,[
                'id'                    => "id",
                'value'                 => $this->request->getPost('id')
            ]));
        
            foreach($form as $element)
            {
                @$cont .= "{$element->render($element->getName())}";
            }

            echo (new Mustache)->render(file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/modals.tpl"),[ 
                'modal-form'     =>  true,
                'info'           =>  true,
                'title'          =>  'Atenção:',
                'text'           =>  $txt,
                'remove'         =>  $isremove,
                'method'         =>  "post",
                'action'         =>  "/admin/artigos/modalF/",
                'contents'       =>  $cont,
            ]);
        
        
            $this->view->disable();

        else:
            return $this->response->redirect("/admin/gcem");
        
        endif;

    }
    
    public function ModalFAction()
    {
        
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->request->getPost('csrf') === $this->session->get("CSRFToken") ):
        
            
            if( $this->request->getPost('action') === "remove" )
            {
                
                if( $this->request->getPost('type') === "category" )
                {
                    
                    ArticleCategories::findFirst($this->request->getPost('id'))->delete();
        
                    $articles = Articles::findByCategory($this->request->getPost('id'));
                    
                    foreach($articles as $article)
                    {
                        $article->category = $this->request->getPost('select');
                        $article->update();
                    }
                     
                    $this->flashSession->error("<div class='title'>REMOVIDO COM SUCESSO !</div> <div class='msg'>Categoria de artigo removido com sucesso !</div> ");

                }
                
            }
        
            if( $this->request->getPost('action') === "modify" )
            {
             
                if( $this->request->getPost('type') === "category" )
                {
                   $category = ArticleCategories::findFirst($this->request->getPost("id"));
        
                        if(Articles::findFirstByUrlrequest( $this->URLGenerator($this->request->getPost("name")) ))
                        {
                            $category->urlrequest = $this->URLGenerator( $this->request->getPost("name").(new \DateTime())->format("-d-m-Y") );
                        }
                        else
                        {
                            $category->urlrequest = $this->URLGenerator($this->request->getPost("name"));
                        }

                        $category->name   = $this->request->getPost("name");
                    $category->update();
                     
                    $this->flashSession->success("<div class='title'>ALTERADO COM SUCESSO !</div> <div class='msg'>Categoria de artigo alterado com sucesso !</div> ");
                }
                
            }
        
        return $this->response->redirect("admin/artigos");
        
        $this->view->disable();
        
        else:
            return $this->response->redirect("admin/artigos");
        
        endif;
        
    }
    

}