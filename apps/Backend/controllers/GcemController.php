<?php

namespace Backend\Controllers;

use Backend\Models\Newsletters            as Newsletters,
    Backend\Models\NewslettersAuthors     as NewslettersAuthors,
    Backend\Models\NewslettersCategories  as NewslettersCategories;

use Mustache_Engine as Mustache;

use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\File,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Hidden;

class GcemController extends ControllerBase
{
    # CHECK IF: SESSION IS OK , IS POST , CSRF TOKEN ( Cross-Site Request Forgery )

    public function indexAction()
    {
        
        $this->view->setVar('newsletters', Newsletters::find() );
        $this->view->setVar('categories', NewslettersCategories::find() );
        $this->view->setVar('authors', NewslettersAuthors::find() );
        $this->view->setVar('csrf', $this->csrf->token );
    }
    
    public function adicionarAction()
    {
        
        $form = new Form();
        
        if ( $this->dispatcher->getParam(0) === "boletim" ):
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Boletim",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));
        
            $form->add(new Select("categories", NewslettersCategories::find(), [
                'id'    => "categories",
                'class' => "form-control",
                'using' => ['_', 'title'],
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));
        
            $form->add(new Select("authors", NewslettersAuthors::find(), [
                'id'    => "category",
                'class' => "form-control",
                'using' => ['_', 'name'],
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
        
        elseif ( $this->dispatcher->getParam(0) === "categoria" ):
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título da Categoria",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));
        
        elseif ( $this->dispatcher->getParam(0) === "autor" ):
        
            $form->add(new Text("name" ,[
                'placeholder'           => "Nome do Autor",
                'class'                 => "form-control",
                'id'                    => "name",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));
        
        endif;
        
            $form->add(new Hidden("csrf" ,[
                'id'                    => "csrf",
                'value'                 => $this->session->get("CSRFToken")
            ]));
        
        $this->view->setVar('form', $form);
        
    }
    
    public function editarAction()
    {
        $newsletter = Newsletters::findFirst($this->dispatcher->getParam(1));
        $category = NewslettersCategories::findFirst($newsletter->category);
        $author = NewslettersAuthors::findFirst($newsletter->author);
        
        $form = new Form();
        
        if ( $this->dispatcher->getParam(0) === "boletim" ):
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Boletim",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $newsletter->title
            ]));
        
            $form->add(new Select("categories", NewslettersCategories::find(), [
                'id'    => "categories",
                'class' => "form-control",
                'using' => ['_', 'title'],
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $newsletter->category
            ]));
        
            $form->add(new Select("authors", NewslettersAuthors::find(), [
                'id'    => "category",
                'class' => "form-control",
                'using' => ['_', 'name'],
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $newsletter->author
            ]));
        
            $form->add(new File("file" ,[
                'accept'    => "image/x-png, image/jpeg",
                'class'     => "form-control",
                'id'        => "file",
            ]));
        
        elseif ( $this->dispatcher->getParam(0) === "categoria" ):
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título da Categoria",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $category->title
            ]));
        
        elseif ( $this->dispatcher->getParam(0) === "autor" ):
        
            $form->add(new Text("name" ,[
                'placeholder'           => "Nome do Autor",
                'class'                 => "form-control",
                'id'                    => "name",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $author->name
            ]));
        
        endif;
        
            $form->add(new Hidden("csrf" ,[
                'id'                    => "csrf",
                'value'                 => $this->session->get("CSRFToken")
            ]));
        
        $this->view->setVar('form', $form);
        
    }
    
    public function criarAction()
    {
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->request->getPost('csrf') === $this->session->get("CSRFToken") ):
        
            if( $this->dispatcher->getParam(0) === "boletim" )
            {
                foreach($this->request->getUploadedFiles() as $file){
                    $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                    $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/documents/{$filename}");
                }

                $newsletter = new Newsletters;
                    $newsletter->title      = $this->request->getPost('title');
                    $newsletter->date       = (new \DateTime())->format("Y-m-d H:i:s");
                    $newsletter->file       = $filename;
                    $newsletter->author     = $this->request->getPost('authors');
                    $newsletter->category   = $this->request->getPost('categories');
                $newsletter->save();
                
            }
            elseif( $this->dispatcher->getParam(0) === "categoria" )
            {
                
                $category = new NewsletterCategories;
                    if(NewsletterCategories::findFirstByUrlrequest( $this->URLGenerator($this->request->getPost("title")) ))
                    {
                        $category->urlrequest = $this->URLGenerator( $this->request->getPost("title").(new \DateTime())->format("-d-m-Y") );
                    }
                    else
                    {
                        $category->urlrequest = $this->URLGenerator($this->request->getPost("title"));
                    }
                    $category->title      = $this->request->getPost("title");
                $category->save();
                
            }
            elseif( $this->dispatcher->getParam(0) === "autor" )
            {
                foreach($this->request->getUploadedFiles() as $file){
                    $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                    $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/team/{$filename}");
                }

                $author = new NewslettersAuthors;
                    if(NewslettersAuthors::findFirstByUrlrequest( $this->URLGenerator($this->request->getPost("name")) ))
                    {
                        $author->urlrequest = $this->URLGenerator( $this->request->getPost("name").(new \DateTime())->format("-d-m-Y") );
                    }
                    else
                    {
                        $author->urlrequest = $this->URLGenerator($this->request->getPost("name"));
                    }
                    $author->name      = $this->request->getPost("name");
                    $author->image     = $filename;
                $author->save();
                
            }

                $this->flashSession->success("<div class='title'>CADASTRADO COM SUCESSO !</div> <div class='msg'>Artigo cadastrado com sucesso !</div> ");
                return $this->response->redirect("admin/gcem");

            
        else:
            return $this->response->redirect("admin/gcem");
        
        endif;
        
    }
    
    public function alterarAction()
    {
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->request->getPost('csrf') === $this->session->get("CSRFToken") ):
        
                $newsletter = Newsletters::findFirst($this->dispatcher->getParam(1));
                        
                    if ($this->request->getUploadedFiles()[0]->getError() == 0)
                    {
                        unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/documents/{$newsletter->file}");

                        foreach ($this->request->getUploadedFiles() as $file){
                            $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                            $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/documents/{$filename}");
                        }

                        $newsletter->file = $filename;
                    }
                    else
                    {
                        $newsletter->file = $newsletter->file;
                    }

                    $newsletter->title      = $this->request->getPost("title");
                    $newsletter->date       = (new \DateTime("now"))->format("Y-m-d H:i:s");
                    $newsletter->category   = $this->request->getPost("categories");
                    $newsletter->author   = $this->request->getPost("authors");
                $newsletter->update();

                $this->flashSession->success("<div class='title'>ALTERADO COM SUCESSO !</div> <div class='msg'>Boletim alterado com sucesso !</div> ");
                return $this->response->redirect("admin/gcem");
        
            
        else:
            return $this->response->redirect("admin/gcem");
        
        endif;
        
    }
    
    public function removerAction()
    {
        if ( $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->dispatcher->getParam(1) === $this->session->get("CSRFToken") ):
        
            $newsletter = Newsletters::findFirst( $this->dispatcher->getParam(0) );

                unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/documents/{$newsletter->file}");

            $newsletter->delete();

            $this->flashSession->success("<div class='title'>REMOVIDO COM SUCESSO !</div> <div class='msg'>Boletim removido com sucesso !</div> ");
            return $this->response->redirect("admin/gcem");
        
        else:
            return $this->response->redirect("admin/gcem");
        
        endif;
    }
    
    # MODAL
    
    public function ModalAction()
    {
        
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") ): 
        
            $category = NewslettersCategories::findFirst($this->request->getPost('id'));
            $author = NewslettersAuthors::findFirst($this->request->getPost('id'));

            $form = new Form();
        
            if( $this->dispatcher->getParam(1) === "remove" )
            {
                
                if( $this->dispatcher->getParam(0) === "category" )
                {
                    $form->add(new Select("select", NewslettersCategories::find(), [
                        'id'    => "select",
                        'class' => "form-control",
                        'using' => ['_', 'title'],
                    ]));
                    
                    $txt = "Escolha uma categoria para que quais quer boletins cadastradas na categoria de <b><u>{$category->title}</u></b> sejam alteradas.";
                    $isremove = true;

                }
                if( $this->dispatcher->getParam(0) === "author" )
                {
                    $form->add(new Select("select", NewslettersAuthors::find(), [
                        'id'    => "select",
                        'class' => "form-control",
                        'using' => ['_', 'name'],
                    ]));
                    
                    $txt = "Escolha um novo autor para que quais quer boletins cadastradas do autor <b><u>{$author->name}</u></b> sejam alteradas.";
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
                        'value'                 => $category->title
                    ]));                    
                    
                    $txt = "Escolha um novo título para a categoria : <b><u>{$category->title}</u></b>.";
                    $isremove = false;

                }
                if( $this->dispatcher->getParam(0) === "author" )
                {
                 
                    $form->add(new Text("name" ,[
                        'placeholder'           => "Autor",
                        'class'                 => "form-control",
                        'id'                    => "name",
                        'data-validate'         => "required",
                        'data-message-required' => "* Este campo é obrigatório.",
                        'value'                 => $author->name
                    ]));                    
                    
                    $txt = "Renomeie o autor : <b><u>{$author->name}</u></b>.";
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
                'action'         =>  "/admin/gcem/modalF/",
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
                    
                    NewslettersCategories::findFirst($this->request->getPost('id'))->delete();
                    foreach(Newsletters::findByCategory($this->request->getPost('id')) as $newsletter)
                    {
                        $newsletter->category = $this->request->getPost('select');
                        $newsletter->update();
                    }
                     
                    $this->flashSession->error("<div class='title'>REMOVIDO COM SUCESSO !</div> <div class='msg'>Categoria de boletim removido com sucesso !</div> ");

                }
                if( $this->request->getPost('type') === "author" )
                {
                    
                    NewslettersAuthors::findFirst($this->request->getPost('id'))->delete();
                    foreach(Newsletters::findByAuthor($this->request->getPost('id')) as $author)
                    {
                        $author->author = $this->request->getPost('select');
                        $author->update();
                    }
                     
                    $this->flashSession->error("<div class='title'>REMOVIDO COM SUCESSO !</div> <div class='msg'>Autor de boletim removido com sucesso !</div> ");

                }
                
            }
        
            if( $this->request->getPost('action') === "modify" )
            {
             
                if( $this->request->getPost('type') === "category" )
                {
                    $category = NewslettersCategories::findFirst($this->request->getPost('id'));
                        if(NewslettersCategories::findFirstByUrlrequest( $this->URLGenerator($this->request->getPost("title")) ))
                        {
                            $category->urlrequest = $this->URLGenerator( $this->request->getPost("title").(new \DateTime())->format("-d-m-Y") );
                        }
                        else
                        {
                            $category->urlrequest = $this->URLGenerator($this->request->getPost("title"));
                        }
                        $category->title = $this->request->getPost('name');
                    $category->update();
                     
                    $this->flashSession->success("<div class='title'>ALTERADO COM SUCESSO !</div> <div class='msg'>Categoria de boletim alterado com sucesso !</div> ");
                }
                if( $this->request->getPost('type') === "author" )
                {
                    $author = NewslettersAuthors::findFirst($this->request->getPost('id'));
                        if(NewslettersAuthors::findFirstByUrlrequest( $this->URLGenerator($this->request->getPost("name")) ))
                        {
                            $author->urlrequest = $this->URLGenerator( $this->request->getPost("name").(new \DateTime())->format("-d-m-Y") );
                        }
                        else
                        {
                            $author->urlrequest = $this->URLGenerator($this->request->getPost("name"));
                        }
                        $author->name = $this->request->getPost('name');
                    $author->update();
                     
                    $this->flashSession->success("<div class='title'>ALTERADO COM SUCESSO !</div> <div class='msg'>Autor de boletim alterado com sucesso !</div> ");
                }
                
            }
        
        return $this->response->redirect("admin/gcem");
        
        $this->view->disable();
        
        else:
            return $this->response->redirect("admin/gcem");
        
        endif;
        
    }
    
}