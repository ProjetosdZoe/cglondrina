<?php

namespace Frontend\Controllers;

use Frontend\Models\Albums as Albums,
    Frontend\Models\Videos as Videos,
    Frontend\Models\Audios as Audios,
    Frontend\Models\AlbumImages as AlbumImages;

class MidiaController extends ControllerBase
{
    
    public function AlbumsAction()
    {
        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css');
        
        $this->view->setVar("albums", Albums::find([ 'order' => 'date' ]) );
        $this->view->setVar("image", $albums);
    }
    
    public function AlbumAction()
    {
        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css');
        
        $album  = Albums::findFirstByUrlrequest($this->dispatcher->getParam(0));
        $images = AlbumImages::findByAlbum($album->_);
        
        $this->view->setVar("images", $images);
        $this->view->setVar("title", $album->title);
    }
    
    public function VideosAction()
    {
        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css');
        
        $videos = Videos::find([ 'order' => 'date' ]);
        
        $this->view->setVar("videos", $videos);
    }
    
    public function AudiosAction()
    {
        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css');
        
        $audios = Audios::find([ 'order' => 'date' ]);
        
        $this->view->setVar("audios", $audios);
    }

}

