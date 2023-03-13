<?php

namespace App\controller\breed;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function authorized( ? array $requester, array $args, array $query): bool
    {
        return true;
    }

    public function process(Request $request, array $args, array $query) : mixed
    {
        $breedId = $args['breedId'];
        $breed = queries\breed\Select::execute( $breedId );
        if( ! $breed ) throw new HttpNotFoundException( $request, 'Breed not found' );
//        $Breed['colors'] = oldqueries\Breed\colors\Select::execute( $breedId );
        return [ 'Breed'=>$breed ];
    }
}
