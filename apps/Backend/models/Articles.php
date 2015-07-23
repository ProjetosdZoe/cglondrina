<?php

namespace Backend\Models;

class Articles extends \Phalcon\Mvc\Model
{

    public function getSource()
    {
        return "news";
    }
    
    public function initialize()
    {
        $this->hasOne("_", "Backend\Models\ArticleCategories", "category");
        $this->hasMany("_", "Backend\Models\ArticleComments", "post" );
    }

}

