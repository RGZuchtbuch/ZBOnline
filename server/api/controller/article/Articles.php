<?php

namespace App\controller\article;

use App\query;
use App\controller\Controller;

class Articles extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {

        $articles = query\Article::getAll();
        return [ 'articles' => $articles ];
    }
}
