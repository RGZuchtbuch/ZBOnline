<?php

namespace App\controller\article;

use App\query;
use App\controller\Controller;
use App\query\Cache;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $article = query\Article::get( $this->args[ 'id' ] );
        if( $article ) {
            return ['article' => $article];
        }
        throw new HttpNotFoundException( $this->request, 'Article not found' );

    }
}
