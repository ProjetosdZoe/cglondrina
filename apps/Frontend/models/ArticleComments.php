<?php

namespace Frontend\Models;

class ArticleComments extends \Phalcon\Mvc\Model
{

    public function getSource()
    {
        return "news_comments";
    }
    
    public function initialize()
    {
        $this->belongsTo("post", "Frontend\Models\Articles", "_" , "ArticleComments");
    }

}

