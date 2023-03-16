<?php
namespace App\Controller\html;

use App\Model;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Results {
    public function __invoke( Request $request, Response $response, array $args ) : Response {
        $query = $request->getQueryParams();
        $districtId = $query[ 'district' ];
        $year = $query[ 'year' ];
        $results = Model\District::results( $districtId, $year );
        $html = $this->render( $results );
        $response->getBody()->write( $html );
        return $response;
    }

    private function render( $results )
    {
        $html = "<table>";
        foreach ($results as $result) {

            $html .= "<tr>";
            $html .= "<td>" . $result['id'] . "</td><td>" . $result['breeders'] . "</td><td>" . $result['pairs'] . "</td>";
            $html .= "</tr>";
        }
        $html .= "</table>";
        return $html;
    }
}
