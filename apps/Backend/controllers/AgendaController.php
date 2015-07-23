<?php

namespace Backend\Controllers;

use Backend\Models\Agendas as Agendas;

use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\File,
    Phalcon\Forms\Element\Hidden;

class AgendaController extends ControllerBase
{
    # CHECK IF: SESSION IS OK , IS POST , CSRF TOKEN ( Cross-Site Request Forgery )

    public function indexAction()
    {
        
        $agendas = Agendas::find();
        $this->view->setVar('agendas', $agendas);
        $this->view->setVar('csrf', $this->csrf->token);

    }
    
    public function adicionarAction()
    {
        
        $this->assets
             ->addJs( $this->template->BJS . "datepicker/bootstrap-datepicker.js")
             ->addJs( $this->template->BJS . "timepicker/bootstrap-timepicker.min.js");
        
        $form = new Form();
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));
        
            $form->add(new Text("date" ,[
                'placeholder'           => "Data de Ocorrência",
                'class'                 => "form-control datepicker",
                'id'                    => "datepicker",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'data-start-view'       => 1,
            ]));
        
            $form->add(new Text("time" ,[
                'placeholder'           => "Horário",
                'class'                 => "form-control timepicker",
                'id'                    => "time",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'data-template'         => "dropdown",
                'data-show-seconds'     => "true",
                'data-default-time'     => "11:25" ,
                'data-show-meridian'    => "false",
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
        
        $this->assets
             ->addJs( $this->template->BJS . "datepicker/bootstrap-datepicker.js")
             ->addJs( $this->template->BJS . "timepicker/bootstrap-timepicker.min.js");
        
        $agenda = Agendas::findFirst($this->dispatcher->getParam(0));
        
        $form = new Form();
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $agenda->title
            ]));
        
            $form->add(new Text("date" ,[
                'placeholder'           => "Data de Ocorrência",
                'class'                 => "form-control datepicker",
                'id'                    => "datepicker",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'data-start-view'       => 1,
                'value'                 => (new \DateTime($agenda->date))->format("d-m-Y")
            ]));
        
            $form->add(new Text("time" ,[
                'placeholder'           => "Horário",
                'class'                 => "form-control timepicker",
                'id'                    => "time",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'data-template'         => "dropdown",
                'data-show-seconds'     => "true",
                'data-show-meridian'    => "false",
                'value'                 => (new \DateTime($agenda->date))->format("H:i:s")
            ]));
        
            $form->add(new Hidden("csrf" ,[
                'id'                    => "csrf",
                'value'                 => $this->session->get("CSRFToken")
            ]));

            $form->add(new File("file" ,[
                'accept'    => "image/x-png, image/jpeg",
                'class'     => "form-control",
                'id'        => "file",
            ]));
        
        $this->view->setVar('form', $form);
        $this->view->setVar('image', $agenda->image);
        
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
                return $this->response->redirect("admin/agenda/adicionar");
                
            }
            else{
                
                foreach($this->request->getUploadedFiles() as $file){
                    $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                    $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/agendas/{$filename}");
                }
                
                $agenda = new Agendas;
                    $agenda->image = $filename;
                    $agenda->title = $this->request->getPost("title");
                    $agenda->date  = (new \DateTime("{$this->request->getPost("date")} {$this->request->getPost("time")}"))->format("Y-m-d H:i:s");
                $agenda->save();

                $this->flashSession->success("<div class='title'>CADASTRADO COM SUCESSO !</div> <div class='msg'>Agenda cadastrada com sucesso !</div> ");
                return $this->response->redirect("admin/agenda");
            }
            
        else:
            return $this->response->redirect("admin/agenda");
        
        endif;
        
    }
    
    public function alterarAction()
    {
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->request->getPost('csrf') === $this->session->get("CSRFToken") ):
            
                $agenda = Agendas::findFirst($this->dispatcher->getParam(0));
                    $agenda->title = $this->request->getPost("title");
                    $agenda->date  = (new \DateTime("{$this->request->getPost("date")} {$this->request->getPost("time")}"))->format("Y-m-d H:i:s");
        
                    if ($this->request->getUploadedFiles()[0]->getError() == 0)
                    {
                        unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/agendas/{$agenda->image}");

                        foreach ($this->request->getUploadedFiles() as $file){
                            $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                            $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/agendas/{$filename}");
                        }

                        $agenda->image = $filename;
                    }
                    else
                    {
                        $agenda->image = $agenda->image;
                    }

                $agenda->update();

                $this->flashSession->success("<div class='title'>ALTERADO COM SUCESSO !</div> <div class='msg'>Agenda alterada com sucesso !</div> ");
                return $this->response->redirect("admin/agenda");
            
        else:
            return $this->response->redirect("admin/agenda");
        
        endif;
        
    }
    
    public function removerAction()
    {
        if ( $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->dispatcher->getParam(1) === $this->session->get("CSRFToken") ):
        
            $agenda = Agendas::findFirst( $this->dispatcher->getParam(0) );

                unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/agendas/{$agenda->image}");

            $agenda->delete();

            $this->flashSession->success("<div class='title'>REMOVIDO COM SUCESSO !</div> <div class='msg'>Agenda removida com sucesso !</div> ");
            return $this->response->redirect("admin/agenda");
        
        else:
            return $this->response->redirect("admin/agenda");
        
        endif;
    }

}