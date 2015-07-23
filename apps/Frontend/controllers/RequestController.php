<?php

namespace Frontend\Controllers;

use Mustache_Engine as Mustache;

use Frontend\Models\Articles as Articles,
    Frontend\Models\ArticleComments as ArticleComments,
    Frontend\Models\ArticleCategories as ArticleCategories,
    Frontend\Models\Newsletter as Newsletter,
    Frontend\Models\Testimonies as Testimonies,
    Frontend\Models\TestimonyComments as TestimonyComments;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as Email;

class RequestController extends ControllerBase
{

    public function ContactUsAction()
    {
        
        if ($this->request->isPost() == true) {
            
            $this->response->setContentType("application/json");
                
                $this->mail->functions->From       = $this->request->getPost("email");
                $this->mail->functions->FromName   = $this->request->getPost("name");

                $this->mail->functions->addAddress($this->mail->email,  $this->mail->name);
                $this->mail->functions->addReplyTo($this->request->getPost("email"), $this->request->getPost("name"));

                $this->mail->functions->Subject = ucwords(strtolower($this->request->getPost("name"))).' entrou em contato pelo portal !';
                
                $result = (new Mustache)->render(file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/mail.tpl"), [ 
                    'name_c'  => $this->request->getPost("name") ,  
                    'message' => $this->request->getPost("message")
                ]);
                
                $this->mail->functions->Body = $result;
                
                if(!$this->mail->functions->send()) {
                    return $this->response->setJsonContent(["status"=>false , "message" => "Erro ao Enviar ! Tente Novamente."]);
                } 
                else {
                    return $this->response->setJsonContent(["status"=>true ,  "message" => "Enviado Com Sucesso!"]);
                }
                
                $this->mail->functions->ClearAddresses();
                

            $this->response->send();
            $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
            
        }
        else{
            return $this->response->redirect("/");
        }
        
    }
    
    public function PedidosAction()
    {
        if ($this->request->isPost() == true) {
            
            $this->response->setContentType("application/json");
                
                $this->mail->functions->From       = $this->request->getPost("email");
                $this->mail->functions->FromName   = $this->request->getPost("name");

                $this->mail->functions->addAddress($this->mail->email,  $this->mail->name);
                $this->mail->functions->addReplyTo($this->request->getPost("email"), $this->request->getPost("name"));

                $this->mail->functions->Subject = ucwords(strtolower($this->request->getPost("name"))).' entrou em contato pelo portal !';
                
                $result = (new Mustache)->render(file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/mail.tpl"), [ 
                    'name_p'  => $this->request->getPost("name") ,  
                    'message' => $this->request->getPost("message")
                ]);
                
                $this->mail->functions->Body = $result;
                
                if(!$this->mail->functions->send()) {
                    return $this->response->setJsonContent(["status"=>false , "message" => "Erro ao Enviar ! Tente Novamente."]);
                } 
                else {
                    return $this->response->setJsonContent(["status"=>true ,  "message" => "Enviado Com Sucesso!"]);
                }
                
                $this->mail->functions->ClearAddresses();
                

            $this->response->send();
            $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
            
        }
        else{
            return $this->response->redirect("/");
        }
    }
    
    public function SubscribeAction()
    {
        
        if ($this->request->isPost() == true) {
            
            $newsletter = new Newsletter;
                $newsletter->name  = $this->request->getPost("name");
                $newsletter->email = $this->request->getPost("email");
                $newsletter->date  = (new \DateTime("now"))->format("Y-m-d H:i:s");
            $newsletter->save();
            
            $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
            
        }
        else{
            return $this->response->redirect("/");
        }
        
    }
    
    public function FetchNewsAction()
    {
        
        if ($this->request->isPost() == true) {
            
            $this->response->setContentType("application/json");
            
            $articles = $this->modelsManager->executeQuery("
            SELECT
            Frontend\Models\Articles.urlrequest,
            Frontend\Models\Articles.title,
            Frontend\Models\Articles.text,
            Frontend\Models\Articles.date,
            Frontend\Models\Articles.image,
            Frontend\Models\ArticleCategories.name as category_name

            FROM Frontend\Models\Articles
            LEFT JOIN Frontend\Models\ArticleCategories ON Frontend\Models\Articles.category = Frontend\Models\ArticleCategories._
            ORDER BY Frontend\Models\Articles._ DESC
            LIMIT 3 OFFSET {$this->request->getPost("offset")}
            ");
            
            $art = [];
            foreach($articles as $article){
                $day = (new \DateTime($article->date))->format("d");
                switch((new \DateTime($article->date))->format("m"))
                {
                    case 1  : $month = "Janeiro"; break;
                    case 2  : $month = "Fevereiro"; break;
                    case 3  : $month = "Março"; break;
                    case 4  : $month = "Abril"; break;
                    case 5  : $month = "Maio"; break;
                    case 6  : $month = "Junho"; break;
                    case 7  : $month = "Julho"; break;
                    case 8  : $month = "Agosto"; break;
                    case 9  : $month = "Setembro"; break;
                    case 10 : $month = "Outubro"; break;
                    case 11 : $month = "Novembro"; break;
                    case 12 : $month = "Dezembro"; break;
                }
                
                $result = (new Mustache)->render(file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/articles.tpl"), [ 
                    'post'          => true ,
                    'day'           => (new \DateTime($article->date))->format("d"),
                    'posted'        => "{$month}, ".(new \DateTime($article->date))->format("Y"),
                    'title'         => $article->title,
                    'category'      => $article->category_name,
                    'text'          => substr($article->text,0,255)."...",
                    'image'         => $article->image,
                    'urlrequest'    => $article->urlrequest
                ]);
                
                array_push($art, $result);
            }
            
            return $this->response->setJsonContent($art);
            
            $this->response->send();
            $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
            
        }
        else{
            return $this->response->redirect("/");
        }
        
    }
    
    public function FetchTestimoniesAction()
    {
        
        if ($this->request->isPost() == true) {
            
            $this->response->setContentType("application/json");
            
            $testimonies = $this->modelsManager->executeQuery("
            SELECT
            Frontend\Models\Testimonies.urlrequest,
            Frontend\Models\Testimonies.title,
            Frontend\Models\Testimonies.text,
            Frontend\Models\Testimonies.date

            FROM Frontend\Models\Testimonies
            ORDER BY Frontend\Models\Testimonies._ DESC
            LIMIT 4 OFFSET {$this->request->getPost("offset")}
            ");
            
            $test = [];
            foreach($testimonies as $testimony){
                $day = (new \DateTime($testimony->date))->format("d");
                switch((new \DateTime($testimony->date))->format("m"))
                {
                    case 1  : $month = "Janeiro"; break;
                    case 2  : $month = "Fevereiro"; break;
                    case 3  : $month = "Março"; break;
                    case 4  : $month = "Abril"; break;
                    case 5  : $month = "Maio"; break;
                    case 6  : $month = "Junho"; break;
                    case 7  : $month = "Julho"; break;
                    case 8  : $month = "Agosto"; break;
                    case 9  : $month = "Setembro"; break;
                    case 10 : $month = "Outubro"; break;
                    case 11 : $month = "Novembro"; break;
                    case 12 : $month = "Dezembro"; break;
                }
                
                $result = (new Mustache)->render(file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/articles.tpl"), [ 
                    'testimony'     => true ,  
                    'name'          => $testimony->author,
                    'day'           => (new \DateTime($testimony->date))->format("d"),
                    'posted'        => "{$month}, ".(new \DateTime($testimony->date))->format("Y"),
                    'title'         => $testimony->title,
                    'text'          => substr($testimony->text,0,255)."...",
                    'urlrequest'    => $testimony->urlrequest
                ]);
                
                array_push($test, $result);
            }
            
            return $this->response->setJsonContent($test);
            
            $this->response->send();
            $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
            
        }
        else{
            return $this->response->redirect("/");
        }
        
    }
    
    public function PostCommentAction()
    {
        
        if ($this->request->isPost() == true) {
            
            $this->response->setContentType("application/json");
            
            if( $this->request->getPost("type") == 1 )
            {
                $article  = Articles::findFirstByUrlrequest($this->request->getPost("url"));
            
                $comment = new ArticleComments();

                    $comment->post = $article->_;
                    $comment->name = $this->request->getPost("name");
                    $comment->date = (new \DateTime("now"))->format("Y-m-d H:i:s");
                    $comment->email = $this->request->getPost("email");
                    $comment->comment = $this->request->getPost("message");

                $comment->save();
            }
            
            if( $this->request->getPost("type") == 2 )
            {
                $testimony  = Testimonies::findFirstByUrlrequest($this->request->getPost("url"));
            
                $comment = new TestimonyComments();

                    $comment->post = $testimony->_;
                    $comment->name = $this->request->getPost("name");
                    $comment->date = (new \DateTime("now"))->format("Y-m-d H:i:s");
                    $comment->email = $this->request->getPost("email");
                    $comment->comment = $this->request->getPost("message");

                $comment->save();
            }
            
            $ctl = [];
            $result = (new Mustache)->render(file_get_contents($_SERVER['DOCUMENT_ROOT']."/templates/articles.tpl"), [ 
                'name'      => $this->request->getPost("name"),
                'date'      => (new \DateTime("now"))->format("Y-m-d H:i:s"),
                'comment'   => $this->request->getPost("message")
            ]);
                
            array_push($ctl, $result);
            
            return $this->response->setJsonContent($ctl);
            
            $this->response->send();
            $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
            
        }
        else{
            return $this->response->redirect("/");
        }
        
    }
    
}

