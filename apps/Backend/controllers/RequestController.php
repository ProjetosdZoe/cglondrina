<?php

namespace Backend\Controllers;

use Backend\Models\Articles            as Articles,
    Backend\Models\ArticleComments     as ArticleComments,
    Backend\Models\ArticleCategories   as ArticleCategories;

use Mustache_Engine as Mustache;

use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Hidden,
    Phalcon\Forms\Element\Select;

class RequestController extends ControllerBase
{
    
    public function IndexAction()
    {
        
    }
    
    public function rmcAction()
    {
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") ):
        
        $category = ArticleCategories::findFirst( $this->request->getPost("id") );
        
        $form = new Form();
            $form->add(new Select("category", ArticleCategories::find(), [
                'id'    => "category",
                'class' => "form-control",
                'using' => ['_', 'name'],
            ]));
        
            $form->add(new Hidden("csrf" ,[
                'id'                    => "csrf",
                'value'                 => $this->session->get("CSRFToken")
            ]));
        
        echo (new Mustache)->render(file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/modals.tpl"),[ 
            'info'   =>  true,
            'title'  =>  'Atenção:',
            'text'   =>  "Escolha uma categoria para que quais quer artigos cadastradas na categoria de <b><u>{$category->name}</u></b> sejam alteradas.",
            
            'modal-form'     =>  true,
            'remove'         =>  true,
            'method'         =>  "post",
            'action'         =>  "/admin/artigos/rmc/{$category->_}",
            'contents'       =>  $form->render("category").$form->render("csrf"),
        ]);
        
        $this->view->disable();
        
        else:
            return $this->response->redirect("/admin");
        
        endif;
    }
    
    public function edcAction()
    {
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") ):
        
            $category = ArticleCategories::findFirst( $this->request->getPost("id") );

            $form = new Form();
                $form->add(new Text("title", [
                    'id'    => "title",
                    'class' => "form-control",
                    'data-validate'         => "required",
                    'data-message-required' => "* Este campo é obrigatório.",
                    'value' => $category->name
                ]));

                $form->add(new Hidden("csrf" ,[
                    'id'                    => "csrf",
                    'value'                 => $this->session->get("CSRFToken")
                ]));

            echo (new Mustache)->render(file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/modals.tpl"),[ 
                'modal-form'     =>  true,
                'method'         =>  "post",
                'action'         =>  "/admin/artigos/edc/{$category->_}",
                'contents'       =>  $form->render("title").$form->render("csrf"),
            ]);

            $this->view->disable();
        
        else:
            return $this->response->redirect("/admin");
        
        endif;
        
    }

}