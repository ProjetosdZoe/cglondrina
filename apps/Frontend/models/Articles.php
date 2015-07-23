<?php

namespace Frontend\Models;

class Articles extends \Phalcon\Mvc\Model
{

    public function getSource()
    {
        return "news";
    }
    
    public function initialize()
    {
        $this->hasMany("_", "Frontend\Models\ArticleComments", "post" );
    }

}

