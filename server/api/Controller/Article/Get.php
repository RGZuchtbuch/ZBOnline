<?php

namespace App\Controller\Article;

use App\Query;
use App\Controller\Controller;
use Exception;
use http\Exception\InvalidArgumentException;
use PDOException;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $article = Query\Article::get( $this->args[ 'id' ] );
        if( $article ) {
            return ['article' => $article];
        }
        throw new HttpNotFoundException( $this->request, 'Article not found' );

    }
}
