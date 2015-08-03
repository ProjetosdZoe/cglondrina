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

    public function audiosAction()
    {
        
        $this->view->setVar('audios', Audios::find() );
        $this->view->setVar('csrf', $this->csrf->token);
    }
    
    public function videosAction()
    {
        
        $this->view->setVar('videos', Videos::find() );
        $this->view->setVar('csrf', $this->csrf->token);
    }
    
    public function albumsAction()
    {
        
        $this->view->setVar('albums', Albums::find() );
        $this->view->setVar('csrf', $this->csrf->token);
    }
     
    public function adicionarAction()
    {
        $form = new Form();
        
        if ( $this->dispatcher->getParam(0) === "album" ):
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Album",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));
        
            $form->add(new File("file" ,[
                'accept'    => "image/x-png, image/jpeg",
                'class'     => "form-control",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));
        
        elseif ( $this->dispatcher->getParam(0) === "audio" ):
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Audio",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));
        
            $form->add(new File("file" ,[
                'accept'    => "audio/*",
                'class'     => "form-control",
            ]));
        
        elseif ( $this->dispatcher->getParam(0) === "video" ):
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Video",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
            ]));
        
            $form->add(new Text("url" ,[
                'placeholder'           => "Link do Video",
                'class'                 => "form-control",
                'id'                    => "title",
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
        
        $album = Albums::findFirst($this->dispatcher->getParam(1));
        $video = Videos::findFirst($this->dispatcher->getParam(1));
        $audio = Audios::findFirst($this->dispatcher->getParam(1));
        $image = AlbumImages::findFirstByAlbum($this->dispatcher->getParam(1));
        
        $form = new Form();
        
        if ( $this->dispatcher->getParam(0) === "album" ):
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Album",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $album->title,
            ]));
        
            $form->add(new File("file" ,[
                'accept'    => "image/x-png, image/jpeg",
                'class'     => "form-control",
            ]));
        
            $this->view->setVar('images', AlbumImages::findByAlbum($this->dispatcher->getParam(1)) );
            $this->view->setVar('csrf', $this->session->get("CSRFToken"));
        
        elseif ( $this->dispatcher->getParam(0) === "audio" ):
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Audio",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $audio->title,
            ]));
        
            $form->add(new File("file" ,[
                'accept'    => "audio/*",
                'class'     => "form-control",
            ]));
        
        elseif ( $this->dispatcher->getParam(0) === "video" ):
        
            $form->add(new Text("title" ,[
                'placeholder'           => "Título do Video",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => $video->title,
            ]));
        
            $form->add(new Text("url" ,[
                'placeholder'           => "Link do Video",
                'class'                 => "form-control",
                'id'                    => "title",
                'data-validate'         => "required",
                'data-message-required' => "* Este campo é obrigatório.",
                'value'                 => "https://www.youtube.com/watch?v={$video->vi}",
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
        
            if ( $this->dispatcher->getParam(0) === "album" ):
                
                $album = new Albums;
                    if(Albums::findFirstByUrlrequest( $this->URLGenerator($this->request->getPost("title")) ))
                    {
                        $album->urlrequest = $this->URLGenerator( $this->request->getPost("title").(new \DateTime())->format("-d-m-Y") );
                    }
                    else
                    {
                        $album->urlrequest = $this->URLGenerator($this->request->getPost("title"));
                    }
                    $album->title = $this->request->getPost("title");
                    $album->date  = (new \DateTime())->format("Y-m-d H:i:s");
                $album->save();
        
                foreach($this->request->getUploadedFiles() as $i => $file){
                    $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                    $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/midia/{$filename}");
                    
                    if( $i == 0 ){
                        $ai = Albums::findFirst($album->_);
                            $ai->image = $filename;
                        $ai->update();
                    }
                    
                    $a_imgs = new AlbumImages;
                        $a_imgs->album = $album->_;
                        $a_imgs->image = $filename;
                    $a_imgs->save();
                }
        
                $this->flashSession->success("<div class='title'>CADASTRADO COM SUCESSO !</div> <div class='msg'>Album cadastrado com sucesso !</div> ");
                return $this->response->redirect("admin/midia/albums");
            
            elseif ( $this->dispatcher->getParam(0) === "audio" ):
        
                switch($this->request->getUploadedFiles()[0]->getError()){
                    case 1 : $e_message = "O arquivo enviado é muito grande!";  break;
                    case 2 : $e_message = "O arquivo enviado é muito grande!";  break;
                    case 3 : $e_message = "O arquivo não foi enviado corretamente!";  break;
                    case 4 : $e_message = "Nenhum arquivo foi enviado!";  break;
                }

                if( $this->request->getUploadedFiles()[0]->getError() != 0 ){

                    $this->flashSession->error("<div class='title'>ERRO AO CADASTRAR !</div> <div class='msg'>{$e_message}</div> ");
                    return $this->response->redirect("admin/midia/adicionar/audio/");

                }
                
                else{
        
                    foreach($this->request->getUploadedFiles() as $file){
                        $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                        $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/audios/{$filename}");
                    }

                    $audio = new Audios;
                        $audio->title = $this->request->getPost("title");
                        $audio->file  = $filename;
                        $audio->date  = (new \DateTime())->format("Y-m-d H:i:s");
                    $audio->save();
                    
                    $this->flashSession->success("<div class='title'>CADASTRADO COM SUCESSO !</div> <div class='msg'>Audio cadastrado com sucesso !</div> ");
                    return $this->response->redirect("admin/midia/audios");
                    
                }
        
            elseif ( $this->dispatcher->getParam(0) === "video" ):
        
                
                if(preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $this->request->getPost("url"), $v))
                {
                    
                    $video = new Videos;
                        $video->title = $this->request->getPost("title");
                        $video->vi = $v[1];
                        $video->date = (new \DateTime())->format("Y-m-d H:i:s");
                    $video->save();
                    
                    $this->flashSession->success("<div class='title'>CADASTRADO COM SUCESSO !</div> <div class='msg'>Video cadastrado com sucesso !</div> ");
                    return $this->response->redirect("admin/midia/videos");
                    
                }
                else
                {
                    
                    $this->flashSession->error("<div class='title'>ERRO AO CADASTRAR !</div> <div class='msg'>Link do video inválido!</div> ");
                    return $this->response->redirect("admin/midia/adicionar/video/");
                    
                }
        
            endif;
            
        else:
            return $this->response->redirect("admin/testemunhos");
        
        endif;
        
    }
    
    public function alterarAction()
    {
        if ( $this->request->isPost() && $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->request->getPost('csrf') === $this->session->get("CSRFToken") ):
        
            if ( $this->dispatcher->getParam(0) === "album" ):
        
                $album = Albums::findFirst( $this->dispatcher->getParam(1) );
                    if(Albums::findFirstByUrlrequest( $this->URLGenerator($this->request->getPost("title")) ))
                    {
                        $album->urlrequest = $this->URLGenerator( $this->request->getPost("title").(new \DateTime())->format("-d-m-Y") );
                    }
                    else
                    {
                        $album->urlrequest = $this->URLGenerator($this->request->getPost("title"));
                    }
                        $album->title = $this->request->getPost("title");
                        $album->date  = (new \DateTime())->format("Y-m-d H:i:s");
        
                    if ($this->request->getUploadedFiles()[0]->getError() == 0)
                    {
                        foreach($this->request->getUploadedFiles() as $id => $file){
                            $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                            $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/midia/{$filename}");
                            
                            if( $i == 0 ){
                                $album->image = $filename;
                            }

                            $a_imgs = new AlbumImages;
                                $a_imgs->album = $this->dispatcher->getParam(1);
                                $a_imgs->image = $filename;
                            $a_imgs->save();
                        }
                    }
                    else
                    {
                        foreach($this->request->getUploadedFiles() as $file){
                            if($file->getSize() != 0){
                                $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                                $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/midia/{$filename}");

                                $a_imgs = new AlbumImages;
                                    $a_imgs->album = $this->dispatcher->getParam(1);
                                    $a_imgs->image = $filename;
                                $a_imgs->save();
                            }
                        }
                    }
                    $album->update();
                    
                    $this->flashSession->success("<div class='title'>ALTERADO COM SUCESSO !</div> <div class='msg'>Album alterado com sucesso !</div> ");
                    return $this->response->redirect("admin/midia/albums");
                
            
            elseif ( $this->dispatcher->getParam(0) === "audio" ):
        
                $audio = Audios::findFirst( $this->dispatcher->getParam(1) );
        
                    if ($this->request->getUploadedFiles()[0]->getError() == 0)
                    {
                        unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/audios/{$audio->file}");

                        foreach ($this->request->getUploadedFiles() as $file){
                            $filename = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ_".time()), 0, 12).'.'.$file->getExtension();
                            $file->moveTo("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/audios/{$filename}");
                        }

                        $audio->file = $filename;
                    }
                    else
                    {
                        $audio->file = $audio->file;
                    }
        
                        $audio->title = $this->request->getPost("title");
                        $audio->date  = (new \DateTime())->format("Y-m-d H:i:s");
                    $audio->update();
                    
                    $this->flashSession->success("<div class='title'>ALTERADO COM SUCESSO !</div> <div class='msg'>Audio alterado com sucesso !</div> ");
                    return $this->response->redirect("admin/midia/audios");
        
        
            elseif ( $this->dispatcher->getParam(0) === "video" ):
        
                if(preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $this->request->getPost("url"), $v))
                {
        
                    $video = Videos::findFirst( $this->dispatcher->getParam(1) );
                        $video->title   = $this->request->getPost("title");
                        $video->vi      = $v[1];
                        $video->date    = (new \DateTime("now"))->format("Y-m-d H:i:s");
                    $video->update();
                
                    $this->flashSession->success("<div class='title'>ALTERADO COM SUCESSO !</div> <div class='msg'>Video alterado com sucesso !</div> ");
                    return $this->response->redirect("admin/midia/videos");
                }
                else
                {
                    $this->flashSession->error("<div class='title'>ERRO AO ALTERAR !</div> <div class='msg'>Link do video inválido!</div> ");
                    return $this->response->redirect("admin/midia/editar/video/{$this->dispatcher->getParam(1)}");
                }
        
            endif;
        
            
        else:
            return $this->response->redirect("admin/index");
        
        endif;
        
    }
    
    public function removerAction()
    {
        if ( $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->dispatcher->getParam(2) === $this->session->get("CSRFToken") ):
        
        
        if ( $this->dispatcher->getParam(0) === "album" ):
        
            $album = Albums::findFirst( $this->dispatcher->getParam(1) );
                foreach(AlbumImages::findByAlbum( $this->dispatcher->getParam(1) ) as $image)
                {
                    unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/midia/{$image->image}");
                    $image->delete();
                }
            $album->delete();           
            
            $this->flashSession->success("<div class='title'>REMOVIDO COM SUCESSO !</div> <div class='msg'>Album removido com sucesso !</div> ");
            return $this->response->redirect("admin/midia/albums");
            
        elseif ( $this->dispatcher->getParam(0) === "audio" ):
        
            $audio = Audios::findFirst( $this->dispatcher->getParam(1) );
        
                    unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/audios/{$audio->file}");
        
            $audio->delete();           
            
            $this->flashSession->success("<div class='title'>REMOVIDO COM SUCESSO !</div> <div class='msg'>Audio removido com sucesso !</div> ");
            return $this->response->redirect("admin/midia/audios");
        
        elseif ( $this->dispatcher->getParam(0) === "video" ):
        
            Videos::findFirst( $this->dispatcher->getParam(1) )->delete();            
            
            $this->flashSession->success("<div class='title'>REMOVIDO COM SUCESSO !</div> <div class='msg'>Video removido com sucesso !</div> ");
            return $this->response->redirect("admin/midia/videos");
        
        endif;
        
        else:
            return $this->response->redirect("admin/index");
        
        endif;
    }
    
    public function rmiAction()
    {
        
        if ( $this->session->has("secure_id") && $this->session->has("CSRFToken") && $this->dispatcher->getParam(2) === $this->session->get("CSRFToken") ):
        
            $album = AlbumImages::findFirst( $this->dispatcher->getParam(0) );

                unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/midia/{$album->image}");

            $album->delete();

            $this->flashSession->success("<div class='title'>REMOVIDO COM SUCESSO !</div> <div class='msg'>Imagem removida com sucesso !</div> ");
            return $this->response->redirect("admin/midia/editar/album/{$this->dispatcher->getParam(1)}");
        
        else:
            return $this->response->redirect("admin/midia/editar/album/{$this->dispatcher->getParam(1)}");
        
        endif;
        
    }

}