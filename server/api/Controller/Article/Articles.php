<?php

namespace App\Controller\Article;

use App\Query;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Articles extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {

        $articles = Query\Article::getAll();
        return [ 'articles' => $articles ];
    }
}
