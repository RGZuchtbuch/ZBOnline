<?php
    declare(strict_types=1);

    require '../api/config/config.php';

    //http://localhost/html/results.php?district=2&year=2023

// cors not needed, as there's no browser nor spa ?
//    header('Access-Control-Allow-Origin: *');
//    header('Access-Control-Allow-Headers:  Accept, Authorization, Content-Type, Origin, X-Requested-With');
//    header('Access-Control-Allow-Methods:  GET');
//    header("Content-Type: application/html");

    function getPdo(): PDO { // taken from class Query
            $pdo = new PDO(
                'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD
            );
            $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC ); // return associated array only
            $pdo->setAttribute( PDO::ATTR_STRINGIFY_FETCHES, false ); // also to get text and numbers instead of always text as template
//            Query::$pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // should be the default true for allowing multiple :year in prepare
        return $pdo;
    }

    function district( int $districtId ) {
        $args = get_defined_vars();
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT district.id, district.name FROM district WHERE district.id=:districtId 
        ");
        if ( $stmt->execute($args) ) {
            return $stmt->fetch(); // only first row
        }
        return null;
    }

    function results( int $districtId, int $year ) : array {
        $args = get_defined_vars();
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT COUNT(*),
                result.districtId AS districtId, section.id AS sectionId, subsection.id AS subsectionId, section.name AS sectionName, section.order, subsection.name AS subsectionName, subsection.order AS subsectionOrder,
                result.id, result.breedId, breed.name AS breedName, result.colorId, color.name AS colorName,
                CAST( SUM( result.breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( result.pairs ) AS UNSIGNED ) AS pairs,
                CAST( SUM( result.layDames ) AS UNSIGNED ) AS layDames, AVG( result.layEggs ) AS layEggs, AVG( result.layWeight ) AS layWeight,
                CAST( SUM( result.broodEggs ) AS UNSIGNED ) AS broodEggs, CAST( SUM( result.broodFertile ) AS UNSIGNED ) AS broodFertile, CAST( SUM( result.broodHatched ) AS UNSIGNED ) AS broodHatched, 
                CAST( SUM( result.showCount ) AS UNSIGNED ) AS showCount, AVG( result.showScore ) AS showScore
            
            FROM result
            LEFT JOIN breed ON breed.id = result.breedId
            LEFT JOIN color ON color.id = result.colorId
            LEFT JOIN section AS subsection ON subsection.id = breed.sectionId
            LEFT JOIN section ON section.id = subsection.parentId
            
            WHERE result.year=:year AND result.districtId IN (
                SELECT DISTINCT child.id FROM district AS parent
                    LEFT JOIN district AS child ON child.id = parent.id OR child.parentId = parent.id
                WHERE parent.id=:districtId OR parent.parentId = :districtId                
            )
            
            GROUP BY subsection.order, breed.name, color.name
            ORDER BY subsection.order, breed.name, color.name
        ");

        if ( $stmt->execute($args) ) {
            return $stmt->fetchAll();
        }
        return [];
    }

    function resultsTree( $results ) : array
    {
        $tree = [ 'sections'=>[] ];
        $sectionId = 0;
        $subsectionId = 0;
        $section = null;
        $subsection = null;
        $breedId = 0;
        $breed = null;

        foreach ($results as $row) {
            if( $row['sectionId'] !== $sectionId ) { // next section
                $sectionId = $row['sectionId'];
                unset( $section ); // to lose ref
                $section = [ 'id'=>$sectionId, 'name'=>$row['sectionName'], 'subsections'=>[] ];
                $tree[ 'sections'][] = & $section; // new section array
            }
            if( $row['subsectionId'] !== $subsectionId ) { // next section
                $subsectionId = $row['subsectionId'];
                unset( $subsection ); // to lose ref
                $subsection = [ 'id'=>$subsectionId, 'name'=>$row['subsectionName'], 'breeds'=>[] ];
                $section[ 'subsections'][] = & $subsection; // new section array
            }
            if( $row[ 'breedId' ] !== $breedId ) { // next Breed
                $breedId = $row[ 'breedId' ];
                unset( $breed ); // to loose ref
                $breed = [ 'id'=>$breedId, 'name'=>$row[ 'breedName' ], 'colors'=>[] ];
                $subsection[ 'breeds' ][] = & $breed; // new Breed array
            }
            $result = [
                'id'=>$row['id'], 'breeders'=>$row['breeders'], 'pairs'=>$row['pairs'],
                'layDames'=>$row['layDames'], 'layEggs'=>$row['layEggs'], 'layWeight'=>$row['layWeight'],
                'broodEggs'=>$row['broodEggs'], 'broodFertile'=>$row['broodFertile'], 'broodHatched'=>$row['broodHatched'],
                'showCount'=>$row['showCount'], 'showScore'=>$row['showScore']
            ];
            if( $row['colorId'] === null ) {
                $breed[ 'result' ] = $result;
            } else {
                $breed['colors'][] = [
                    'id' => $row['colorId'], 'name' => $row['colorName'], 'result'=> $result
                ];
            }
        }
        return $tree;
    }

    function render( $district, $year, $results ) {
        print( "
            <!DOCTYPE html>
            <html lang='en'>
              <head>
                <title>RGZuchtbuch</title>
                <meta charset='UTF-8' />
                <meta name='viewport' content='width=device-width, initial-scale=1.0' />
              </head>
              <body>        
        " );
        print( "<div>RGZuchtbuch results for district [{$district['id']}] {$district['name']} and year {$year}</div>" );
        print( "<table border=1>" );
        print( "
            <thead>
                <tr>
                    <td>Id</td><td>Rasse</td><td>Farbe</td><td>Zuchten</td><td>Stämme</td>
                    <td>Hennen</td><td>Eier/J</td><td>Eigewicht</td>
                    <td>Eingelegt</td><td>Befruchtet</td><td>Geschlüpft</td>
                    <td>Ausgestellt</td><td>Punkte</td>
                </tr>
            </thead>
        " );
        print( "<tbody>" );
        foreach( $results['sections'] as $section ) {
            $total = [ 'breeders'=>0, 'pairs'=>0, 'layDames'=>0, 'layEggs'=>null, 'layWeight'=>null, 'broodEggs'=>0, 'broodFertile'=>0, 'broodHatched'=>0, 'showCount'=>0, 'showScore'=>null ];
            print( "<tr><td>1</td><td>{$section['name']}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>" );
            foreach( $section['subsections'] as $subsection ) {
                print( "<tr><td>2</td><td>{$subsection['name']}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>" );
                foreach( $subsection['breeds'] as $breed ) {
                    print("<tr><td>3</td><td>{$breed['name']}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>");
                    foreach ($breed['colors'] as $color) {
                        $result = & $color['result'];
                        $total['breeders'] += $result['breeders'];
                        $total['pairs'] += $result['pairs'];
                        $total['layDames'] += $result['layDames'];
                        $total['layEggs'] += $result['breeders'] * $result['layEggs']; // needs div by total breeders again
                        $total['layWeight'] += $result['breeders'] * $result['layWeight']; // needs div by total breeders
                        $total['broodEggs'] += $result['broodEggs'];
                        $total['broodFertile'] += $result['broodFertile'];
                        $total['broodHatched'] += $result['broodHatched'];
                        $total['showCount'] += $result['showCount'];
                        $total['showScore'] += $result['breeders'] * $result['showScore']; // needs div
                        print( "
                            <tr><td>4</td>
                                <td></td><td>{$color['name']}</td>
                                <td>{$color['result']['breeders']}</td><td>{$color['result']['pairs']}</td>
                                <td>{$color['result']['layDames']}</td><td>{$color['result']['layEggs']}</td><td>{$color['result']['layWeight']}</td>
                                <td>{$color['result']['broodEggs']}</td><td>{$color['result']['broodFertile']}</td><td>{$color['result']['broodHatched']}</td>
                                <td>{$color['result']['showCount']}</td><td>{$color['result']['showScore']}</td>
                            </tr>
                        ");
                    }
                }
            }
            $total['layEggs'] = $total['layEggs'] / $total['breeders'];
            $total['layWeight'] = $total['layWeight'] / $total['breeders'];
            $total['showScore'] = $total['showScore'] / $total['breeders'];
            print( "
                <tr><td>5</td>
                    <td>{$section['name']} gesamt</td><td></td>
                    <td>{$total['breeders']}</td><td>{$total['pairs']}</td>
                    <td>{$total['layDames']}</td><td>{$total['layEggs']}</td><td>{$total['layWeight']}</td>
                    <td>{$total['broodEggs']}</td><td>{$total['broodFertile']}</td><td>{$total['broodHatched']}</td>
                    <td>{$total['showCount']}</td><td>{$total['showScore']}</td>
                </tr>
            ");
            print( "</tbody>" );
        }
        print( "</table>" );
        print( "
              </body>
            </html>        
        " );
    }

    $pdo = getPdo();

    $query = $_GET;
    $districtId = +( $query[ 'district' ] ?? null ); // null or number by +
    $year = +( $query[ 'year' ] ?? null ); // null or number

    if( $districtId && $year ) {
        $district = district( $districtId );
        $results = resultsTree( results($districtId, $year) );
        //print( json_encode( $results ) );
        if( $district && $results ) {
            render($district, $year, $results);
        } else {
            print("Error, district {$districtId} not found");
            http_response_code(404);
        }
    } else {
        print("Error, required format: https://rgzuchtbuch.de/html/results.php?year=2023&district=6");
        http_response_code(400);
    }








