<?php

namespace Backend\Controllers;

use Backend\Models\Articles            as Articles,
    Backend\Models\ArticleComments     as ArticleComments,
    Backend\Models\ArticleCategories   as ArticleCategories;

use Mustache_Engine as Mustache;

use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Hidden,
    Phalcon\Forms\Element\Select;

class RequestController extends ControllerBase
{
    
    public function IndexAction()
    {
        
    }

}