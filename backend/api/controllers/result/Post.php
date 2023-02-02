<?php

namespace App\controllers\result;

use App\queries;
use App\queries\Query;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;

class Post extends Controller
{
    public function authorized(? array $requester, array $args, array $query): bool
    {
        return true; //isset( $requester['isAdmin'] ) && $requester['isAdmin'];
    }

    public function process(Request $request, array $args, array $query) : mixed
    {
        $result = $this->getData($request);
        $success = false;
        if( $this->isCleared( $result ) ) {
            $success = queries\result\Delete::execute( $result['id'] );
            $result['id'] = null; // signals deleted
        } else if( $result['id'] === null ) {
            $success = queries\result\Insert::execute(
                $result['reportId'], $result['districtId'], $result['year'], $result['group'],
                $result['breedId'], $result['colorId'],
                $result['breeders'], $result['pairs'],
                $result['layDames'], $result['layEggs'], $result['layWeight'],
                $result['broodEggs'], $result['broodFertile'], $result['broodHatched'],
                $result['showCount'], $result['showScore']
            );
            $result['id'] = Query::lastInsertId('id');
        } else {
            $success = queries\result\Update::execute(
                $result['id'],
                $result['reportId'], $result['districtId'], $result['year'], $result['group'],
                $result['breedId'], $result['colorId'],
                $result['breeders'], $result['pairs'],
                $result['layDames'], $result['layEggs'], $result['layWeight'],
                $result['broodEggs'], $result['broodFertile'], $result['broodHatched'],
                $result['showCount'], $result['showScore']
            );
        }
        if ($success) {
            return ['id' => $result['id'] ];
        }
        throw new HttpInternalServerErrorException( $request, "Query result\Post failed" );
    }

    // is no relevant content then delete the result ( is id != null )
    private function isCleared( array $result ) {
        return
            $result['breeders'] === null && $result['pairs'] === null &&
            $result['layDames'] === null && $result['layEggs'] === null && $result['layWeight'] === null &&
            $result['broodEggs'] === null && $result['broodFertile'] === null && $result['broodHatched'] === null &&
            $result['showCount'] === null && $result['showScore'] === null;
    }
}
