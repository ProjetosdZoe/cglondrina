<?php

namespace Frontend\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
     public function initialize()
     {
        
        $this->assets
             ->addCss('//fonts.googleapis.com/css?family=Lato:300,400,700,900', false)
             ->addCss('assets/frontend/style/jquery/magnific-popup.css')
             ->addCss('assets/frontend/style/font-awesome.min.css')
             ->addCss('assets/frontend/style/animate/animate.css')
             ->addCss('assets/frontend/style/bootstrap.min.css')
             ->addCss('assets/frontend/style/bootstrap.inline-responsive.css')
             ->addCss('assets/frontend/style/style.css')
             ->addCss('assets/frontend/style/rs-plugin/settings.css')
             ->addJs('assets/frontend/js/jquery/jquery-1.11.0.min.js')
             ->addJs('assets/frontend/js/jquery/jquery.parallax-1.1.3.js')
             ->addJs('assets/frontend/js/jquery/jquery.countTo.js')
             ->addJs('assets/frontend/js/jquery/jquery.transit.min.js')
             ->addJs('assets/frontend/js/jquery/jquery.isotope.min.js')
             ->addJs('assets/frontend/js/jquery/jquery.flexslider-min.js')
             ->addJs('assets/frontend/js/jquery/jquery.magnific-popup.min.js')
             ->addJs('assets/frontend/js/jquery/jquery.carouFredSel-6.2.1-packed.js')
             ->addJs('assets/frontend/js/jquery/jquery.mousewheel.min.js')
             ->addJs('assets/frontend/js/jquery/jquery.touchSwipe.min.js')
             ->addJs('assets/frontend/js/jquery/jquery.mask.min.js')
             ->addJs('assets/frontend/js/jquery/jquery.countdown.js')
             ->addJs('assets/frontend/js/jquery/jquery.filtr.js')
             ->addJs('assets/frontend/js/jquery/scrollIt.min.js')
             ->addJs('assets/frontend/js/rs-plugin/jquery.themepunch.tools.min.js')
             ->addJs('assets/frontend/js/rs-plugin/jquery.themepunch.revolution.min.js')
             ->addJs('assets/frontend/js/bootstrap/bootstrap.min.js')
             ->addJs('assets/frontend/js/custom.script.js');
         
    }

}
