<?php

namespace Frontend\Controllers;

use Frontend\Models\Articles as Articles,
    Frontend\Models\ArticleComments as ArticleComments,
    Frontend\Models\ArticleCategories as ArticleCategories;

class ArtigosController extends ControllerBase
{

    public function indexAction()
    {

        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css');
                
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
        LIMIT 3 OFFSET 0
        ");

        $topviews = Articles::find([
            'order' => 'views DESC',
            'limit' => 3 
        ]);

        $categories = ArticleCategories::find([
            "order" => "name"
        ]);

        $this->view->setVar("categories", $categories);
        $this->view->setVar("articles", $articles);
        $this->view->setVar("topviews", $topviews);
        $this->view->setVar("comments", $comments);

    }
    
    public function PostAction()
    {
        
        $this->assets
             ->addCss('assets/frontend/style/secondary.page.css')
             ->addCss('assets/frontend/style/blog.css');
        
        $article  = Articles::findFirstByUrlrequest($this->dispatcher->getParam(0));
        $category = ArticleCategories::findFirst($article->category);
        $comments = ArticleComments::find([
            "post   =  '{$article->_}' ",
            "order" => "_ DESC"
        ]);
        
        $article->views = ($article->views + 1);
        $article->update();

        $this->view->setVar("article", $article);
        $this->view->setVar("comments", $comments);
        $this->view->setVar("category", $category);
        
    }

}
