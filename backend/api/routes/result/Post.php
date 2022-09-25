<?php

namespace App\routes\result;

use App\Queries;
use App\routes\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class Post extends Controller
{
    public function preAuthorized( ? array &$requester, array &$args): bool
    {
        return true; //isset( $requester['isAdmin'] ) && $requester['isAdmin'];
    }

    public function process(Request $request, array $args) : mixed
    {
        $result = $this->getData( $request );
        $id = Queries\Result::insert(
            $result['year'], $result['breederId'], $result['districtId'], $result['group'],
            $result['sectionId'], $result['breedId'], $result['colorId'],
            $result['layDames'], $result['layEggs'], $result['layWeight'],
            $result['broodEggs'], $result['broodFertile'], $result['broodHatched'],
            $result['showCount'], $result['showScore']
        );
        if( $id == null ) throw new HttpBadRequestException( $request, "Post failed");
        return $id;
    }

}
