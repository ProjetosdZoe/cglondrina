<?php

namespace Backend\Models;

class ArticleCategories extends \Phalcon\Mvc\Model
{

    public function getSource()
    {
        return "news_categories";
    }
    
    public function initialize()
    {
        $this->belongsTo("category", "Backend\Models\Articles", "_" , "ArticleCategory");
    }

}

