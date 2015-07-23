<?php

namespace Frontend\Controllers;

class SitemapController extends ControllerBase
{

    public function indexAction()
        {
        
            $this->response->setContentType("application/xml");
            
            $sitemap = new \DOMDocument("1.0", "UTF-8");

            $urlset = $sitemap->createElement('urlset');
            $urlset->setAttribute('xmlns'    , 'http://www.sitemaps.org/schemas/sitemap/0.9');
            $urlset->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
            $sitemap->appendChild($urlset);

            $url = $sitemap->createElement('url');
            $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME']));
            $url->appendChild($sitemap->createElement('changefreq', 'daily'));
            $url->appendChild($sitemap->createElement('priority', '1.0'));
            $urlset->appendChild($url);
        
            $url = $sitemap->createElement('url');
            $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/artigos'));
            $url->appendChild($sitemap->createElement('changefreq', 'daily'));
            $url->appendChild($sitemap->createElement('priority', '1.0'));
            $urlset->appendChild($url);
        
                $url = $sitemap->createElement('url');
                $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/comunidade/associacao'));
                $url->appendChild($sitemap->createElement('changefreq', 'daily'));
                $url->appendChild($sitemap->createElement('priority', '1.0'));
                $urlset->appendChild($url);

                $url = $sitemap->createElement('url');
                $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/comunidade/declaracao'));
                $url->appendChild($sitemap->createElement('changefreq', 'daily'));
                $url->appendChild($sitemap->createElement('priority', '1.0'));
                $urlset->appendChild($url);

                $url = $sitemap->createElement('url');
                $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/comunidade/familia'));
                $url->appendChild($sitemap->createElement('changefreq', 'daily'));
                $url->appendChild($sitemap->createElement('priority', '1.0'));
                $urlset->appendChild($url);

                $url = $sitemap->createElement('url');
                $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/comunidade/fundacao'));
                $url->appendChild($sitemap->createElement('changefreq', 'daily'));
                $url->appendChild($sitemap->createElement('priority', '1.0'));
                $urlset->appendChild($url);

                $url = $sitemap->createElement('url');
                $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/comunidade/logo'));
                $url->appendChild($sitemap->createElement('changefreq', 'daily'));
                $url->appendChild($sitemap->createElement('priority', '1.0'));
                $urlset->appendChild($url);

                $url = $sitemap->createElement('url');
                $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/comunidade/missao'));
                $url->appendChild($sitemap->createElement('changefreq', 'daily'));
                $url->appendChild($sitemap->createElement('priority', '1.0'));
                $urlset->appendChild($url);

                $url = $sitemap->createElement('url');
                $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/comunidade/modelo'));
                $url->appendChild($sitemap->createElement('changefreq', 'daily'));
                $url->appendChild($sitemap->createElement('priority', '1.0'));
                $urlset->appendChild($url);

                $url = $sitemap->createElement('url');
                $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/comunidade/teologia'));
                $url->appendChild($sitemap->createElement('changefreq', 'daily'));
                $url->appendChild($sitemap->createElement('priority', '1.0'));
                $urlset->appendChild($url);
        
                $url = $sitemap->createElement('url');
                $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/comunidade/valores'));
                $url->appendChild($sitemap->createElement('changefreq', 'daily'));
                $url->appendChild($sitemap->createElement('priority', '1.0'));
                $urlset->appendChild($url);
        
                $url = $sitemap->createElement('url');
                $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/comunidade/visao'));
                $url->appendChild($sitemap->createElement('changefreq', 'daily'));
                $url->appendChild($sitemap->createElement('priority', '1.0'));
                $urlset->appendChild($url);
        
            $url = $sitemap->createElement('url');
            $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/gcem'));
            $url->appendChild($sitemap->createElement('changefreq', 'daily'));
            $url->appendChild($sitemap->createElement('priority', '1.0'));
            $urlset->appendChild($url);
        
                $url = $sitemap->createElement('url');
                $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/gcem/boletins'));
                $url->appendChild($sitemap->createElement('changefreq', 'daily'));
                $url->appendChild($sitemap->createElement('priority', '1.0'));
                $urlset->appendChild($url);
        
                $url = $sitemap->createElement('url');
                $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/gcem/mapa'));
                $url->appendChild($sitemap->createElement('changefreq', 'daily'));
                $url->appendChild($sitemap->createElement('priority', '1.0'));
                $urlset->appendChild($url);
        
                $url = $sitemap->createElement('url');
                $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/gcem/ctl'));
                $url->appendChild($sitemap->createElement('changefreq', 'daily'));
                $url->appendChild($sitemap->createElement('priority', '1.0'));
                $urlset->appendChild($url);
        
            $url = $sitemap->createElement('url');
            $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/midia/albums'));
            $url->appendChild($sitemap->createElement('changefreq', 'daily'));
            $url->appendChild($sitemap->createElement('priority', '1.0'));
            $urlset->appendChild($url);
        
            $url = $sitemap->createElement('url');
            $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/midia/audios'));
            $url->appendChild($sitemap->createElement('changefreq', 'daily'));
            $url->appendChild($sitemap->createElement('priority', '1.0'));
            $urlset->appendChild($url);
        
            $url = $sitemap->createElement('url');
            $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/midia/videos'));
            $url->appendChild($sitemap->createElement('changefreq', 'daily'));
            $url->appendChild($sitemap->createElement('priority', '1.0'));
            $urlset->appendChild($url);
        
            $url = $sitemap->createElement('url');
            $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/ministerios'));
            $url->appendChild($sitemap->createElement('changefreq', 'daily'));
            $url->appendChild($sitemap->createElement('priority', '1.0'));
            $urlset->appendChild($url);
        
            $url = $sitemap->createElement('url');
            $url->appendChild($sitemap->createElement('loc', $_SERVER['SERVER_NAME'].'/testemunhos'));
            $url->appendChild($sitemap->createElement('changefreq', 'daily'));
            $url->appendChild($sitemap->createElement('priority', '1.0'));
            $urlset->appendChild($url);
        
            $this->response->setContent($sitemap->saveXML());
        
            $this->view->disable();
            $this->response->send();
        
        }

}