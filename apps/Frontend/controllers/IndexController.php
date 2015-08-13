<?php

namespace Frontend\Controllers;

use Frontend\Models\Agendas     as Agendas,
    Frontend\Models\Banners     as Banners,
    Frontend\Models\Members     as Members,
    Frontend\Models\Articles    as Articles,
    Frontend\Models\Ministries  as Ministries,
    Frontend\Models\Testimonies as Testimonies;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        
        $banners = Banners::find([
            'order' => '_ DESC' 
        ]);
        $members = Members::find([
            'order' => 'name'
        ]);
        $article = Articles::findFirst([
            'order' => 'views DESC'
        ]);
        $testimonies = Testimonies::find([
            'limit' => 5
        ]);
        $agendas = Agendas::find([
            'limit' => 6,
            'order' => 'date'
        ]);
        $ministries = Ministries::find([
            'limit' => 6
        ]);
        $nextAgenda = Agendas::findFirst([
            'order' => 'date'
        ]);
        
        # REMOVE AGENDA IF DATE HAS PASSED
        $currentDate = new \DateTime("now");
        foreach($agendas as $agenda)
        {
            if($currentDate > (new \DateTime($agenda->date)))
            {
                unlink("{$_SERVER['DOCUMENT_ROOT']}/public/assets/frontend/images/agendas/{$agenda->image}");
                $agenda->delete();
            }
        }
        
        $this->view->setVar("banners",$banners);
        $this->view->setVar("article",$article);
        $this->view->setVar("members",$members);
        $this->view->setVar("agendas",$agendas);
        $this->view->setVar("ministries",$ministries);
        $this->view->setVar("nextAgenda",$nextAgenda);
        $this->view->setVar("testimonies",$testimonies);
        
    }
    
    public function errorAction()
    {
        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css');
        
        $this->view->pick("index/404");
        
    }

}

