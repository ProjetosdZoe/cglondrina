<?php

namespace Backend\Models;

class ArticleComments extends \Phalcon\Mvc\Model
{

    public function getSource()
    {
        return "news_comments";
    }
    
    public function initialize()
    {
        $this->belongsTo("post", "Backend\Models\Articles", "_" , "ArticleComments");
    }

}

