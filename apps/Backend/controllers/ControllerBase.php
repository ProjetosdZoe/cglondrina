<?php

namespace Backend\Controllers;

use Backend\Models\Users as Users; 

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function initialize()
    {
        # CSS
        $this->assets
             ->addCss('http://fonts.googleapis.com/css?family=Arimo:400,700,400italic', false)
             ->addCss($this->template->BCSS . "fonts/linecons/css/linecons.css")
             ->addCss($this->template->BCSS . "fonts/fontawesome/css/font-awesome.min.css")
             ->addCss($this->template->BCSS . "bootstrap.css")
             ->addCss($this->template->BCSS . "xenon-core.css")
             ->addCss($this->template->BCSS . "xenon-forms.css")
             ->addCss($this->template->BCSS . "xenon-components.css")
             ->addCss($this->template->BCSS . "xenon-skins.css")
             ->addCss($this->template->BCSS . "custom.css");
        
        # JS
        $this->assets
             ->addJs( $this->template->BJS . "jquery-1.11.1.min.js")
             ->addJs( $this->template->BJS . "jquery-ui/jquery-ui.min.js")
             ->addJs( $this->template->BJS . "jquery-validate/jquery.validate.min.js")
             ->addJs( $this->template->BJS . "bootstrap.min.js")
             ->addJs( $this->template->BJS . "toastr/toastr.min.js")
             ->addJs( $this->template->BJS . "jQuery.filtr.js")
             ->addjs( $this->template->BJS . "TweenMax.min.js")
             ->addjs( $this->template->BJS . "joinable.js")
             ->addjs( $this->template->BJS . "resizeable.js")
             ->addjs( $this->template->BJS . "xenon-api.js")
             ->addjs( $this->template->BJS . "xenon-toggles.js")
             ->addjs( $this->template->BJS . "moment.min.js")
             ->addjs( $this->template->BJS . "xenon-custom.js")
             ->addjs( $this->template->BJS . "custom.js")
             ->addjs( $this->template->BJS . "ckeditor/ckeditor.js");
        
        # if session then set accessible vars
        if ($this->session->has("secure_id")):
            
            $user = Users::findFirst($this->session->get("secure_id"));
            
            $this->view->setVar("user" , $user);
        else:
            
            if($this->router->getControllerName() != 'login'){
                return $this->response->redirect("admin/login");
            }
            
        endif;
         
    }
    
    public function URLGenerator($str)
    {
        setlocale(LC_ALL, 'en_US.UTF8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_| -]+/", '-', $clean);

        return $clean;
    }

}
