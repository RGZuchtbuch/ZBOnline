<?php

namespace App\Query;

class Result extends Query
{
    public static function get( $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, reportId, districtId, `year`, `group`, breedId, colorId, breeders, pairs, layDames, layEggs, broodEggs, broodFertile, broodHatched, showCount, showScore
            FROM result
            WHERE id=:id
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function new(
        ? int $reportId, int $districtId, int $year, string $group,
        int $breedId, ? int $colorId,
        int $breeders, ? int $pairs,
        ? int $layDames, ? float $layEggs, ? float $layWeight,
        ? int $broodEggs, ? int $broodFertile, ? int $broodHatched,
        ? int $showCount, ? float $showScore,
        int $modifier
    ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO result ( reportId, districtId, `year`, `group`, breedId, colorId, breeders, pairs, layDames, layEggs, layWeight, broodEggs, broodFertile, broodHatched, showCount, showScore, modifier ) 
            VALUES ( :reportId, :districtId, :year, :group, :breedId, :colorId, :breeders, :pairs, :layDames, :layEggs, :layWeight, :broodEggs, :broodFertile, :broodHatched, :showCount, :showScore, :modifier )
        ' );
        return Query::insert( $stmt, $args );
    }


    public static function set(
        int $id, ? int $reportId, int $districtId, int $year, string $group,
        int $breedId, ? int $colorId,
        int $breeders, ? int $pairs,
        ? int $layDames, ? float $layEggs, ? float $layWeight,
        ? int $broodEggs, ? int $broodFertile, ? int $broodHatched,
        ? int $showCount, ? float $showScore,
        int $modifier
    ) : bool {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE  result
            SET reportId=:reportId, districtId=:districtId, `year`=:year, `group`=:group, breedId=:breedId, colorId=:colorId, breeders=:breeders, pairs=:pairs, layDames=:layDames, layEggs=:layEggs, layWeight=:layWeight, broodEggs=:broodEggs, broodFertile=:broodFertile, broodHatched=:broodHatched, showCount=:showCount, showScore=:showScore, modifier=:modifier
            WHERE id=:id
        ' );
        return Query::update( $stmt, $args );
    }

    public static function del( int $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM result
            WHERE id=:id
        ' );
        return Query::delete( $stmt, $args );
    }



    // for results page
    public static function districtsForColor( $year, $colorId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( " 
            SELECT 
                COUNT(*) AS `count`, results.colorId, 
                district.id, district.name, district.latitude, district.longitude,
                
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                
                SUM( layDames) AS layDames, 
#                SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs,
                SUM( IF( breeders AND layEggs, breeders * layEggs, NULL ) )  /  SUM( IF( breeders AND layEggs, breeders, NULL ) ) AS layEggs,
#                SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                SUM( IF( breeders AND layWeight, breeders * layWeight, NULL ) )  /  SUM( IF( breeders AND layEggs, breeders, NULL ) ) AS layWeight,
                
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, 
                CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, 
                CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched, 
                
                
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
#                SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
                SUM( IF( showCount AND showScore, showCOunt * showScore, NULL ) )  /  SUM( IF( showCount AND showScore, showCount, NULL ) ) AS showScore
            
            FROM district
            LEFT JOIN (
                SELECT  result.*, district.rootId 
                FROM result 
                    LEFT JOIN district ON district.id=result.districtId
                WHERE `year`=:year AND colorId=:colorId               
            ) AS results ON results.rootId=district.id
            GROUP BY district.rootId
            ORDER BY district.name

        " );
        return Query::selectArray( $stmt, $args );
    }

    //for view page
    public static function districtsForBreed( $year, $breedId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( " 
            SELECT 
                COUNT(*) AS `count`, results.colorId, 
                district.id, district.name, district.latitude, district.longitude,
                
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                
                SUM( layDames) AS layDames, 
#                SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs,
                SUM( IF( breeders AND layEggs, breeders * layEggs, NULL ) )  /  SUM( IF( breeders AND layEggs, breeders, NULL ) ) AS layEggs,
#                SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                SUM( IF( breeders AND layWeight, breeders * layWeight, NULL ) )  /  SUM( IF( breeders AND layEggs, breeders, NULL ) ) AS layWeight,
                
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, 
                CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, 
                CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
#                SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
                SUM( IF( showCount AND showScore, showCOunt * showScore, NULL ) )  /  SUM( IF( showCount AND showScore, showCount, NULL ) ) AS showScore

            FROM district
            LEFT JOIN (
                SELECT  result.*, district.rootId 
                FROM result 
                    LEFT JOIN district ON district.id=result.districtId
                WHERE `year`=:year AND breedId=:breedId               
            ) AS results ON results.rootId=district.id
            GROUP BY district.rootId
            ORDER BY district.name

        " );

        return Query::selectArray( $stmt, $args );
    }

    // for view page
    public static function districtsForSection( $year, $sectionId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT 
                COUNT(*) AS `count`, 
                district.rootId AS id, district.name, district.latitude, district.longitude, avg( results.year),
            
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                
                SUM( layDames) AS layDames, 
#                SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs,
                SUM( IF( breeders AND layEggs, breeders * layEggs, NULL ) )  /  SUM( IF( breeders AND layEggs, breeders, NULL ) ) AS layEggs,
#                SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                SUM( IF( breeders AND layWeight, breeders * layWeight, NULL ) )  /  SUM( IF( breeders AND layEggs, breeders, NULL ) ) AS layWeight,
                
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, 
                CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, 
                CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
#                SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
                SUM( IF( showCount AND showScore, showCOunt * showScore, NULL ) )  /  SUM( IF( showCount AND showScore, showCount, NULL ) ) AS showScore
            
            FROM district 
            
            LEFT JOIN (
                SELECT breed.sectionId, result.*
                FROM result 
                    LEFT JOIN breed ON breed.id = result.breedId	
                WHERE result.year=:year
            ) AS results ON results.districtId = district.id AND results.sectionId IN (
                SELECT DISTINCT child.id FROM section AS parent
                    LEFT JOIN section AS child ON child.parentId = parent.id OR child.id=parent.id
                WHERE parent.id=:sectionId OR parent.parentId=:sectionId
            )
            GROUP BY district.rootId      
            ORDER BY district.name
        " );

        return Query::selectArray($stmt, $args );
    }


    public static function yearsForColor( $districtId, $colorId ) {
        $startYear = START_YEAR;
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT years.year, result.colorId,
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                
                SUM( layDames) AS layDames, 
#                SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs,
                SUM( IF( breeders AND layEggs, breeders * layEggs, NULL ) )  /  SUM( IF( breeders AND layEggs, breeders, NULL ) ) AS layEggs,
#                SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                SUM( IF( breeders AND layWeight, breeders * layWeight, NULL ) )  /  SUM( IF( breeders AND layEggs, breeders, NULL ) ) AS layWeight,
                
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, 
                CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, 
                CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
#                SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
                SUM( IF( showCount AND showScore, showCOunt * showScore, NULL ) )  /  SUM( IF( showCount AND showScore, showCount, NULL ) ) AS showScore

            
            FROM (
                SELECT @year:=YEAR( NOW() )+1
            ) AS init, (
                SELECT @year:=@year-1 AS `year`
                FROM color
                WHERE @year > :startYear
            ) AS years
                LEFT JOIN result ON result.year = years.year AND result.colorId=:colorId AND result.districtId IN (
                    SELECT DISTINCT child.id FROM district AS parent
                        LEFT JOIN district AS child ON child.parentId = parent.id OR child.id = parent.id # add children and repeat parent
                    WHERE parent.id=:districtId OR parent.parentId = :districtId                                          
                )
                        
            GROUP BY years.year
            ORDER BY years.year
        " );

        return Query::selectArray( $stmt, $args );
    }
    public static function yearsForBreed( $districtId, $breedId ) {
        $startYear = START_YEAR;
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT years.year, result.colorId,
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                
                SUM( layDames) AS layDames, 
#                SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs,
                SUM( IF( breeders AND layEggs, breeders * layEggs, NULL ) )  /  SUM( IF( breeders AND layEggs, breeders, NULL ) ) AS layEggs,
#                SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                SUM( IF( breeders AND layWeight, breeders * layWeight, NULL ) )  /  SUM( IF( breeders AND layEggs, breeders, NULL ) ) AS layWeight,
                
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, 
                CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, 
                CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
#                SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
                SUM( IF( showCount AND showScore, showCOunt * showScore, NULL ) )  /  SUM( IF( showCount AND showScore, showCount, NULL ) ) AS showScore

            FROM (
                SELECT @year:=YEAR( NOW() )+1
            ) AS init, (
                SELECT @year:=@year-1 AS `year`
                FROM color
                WHERE @year > :startYear
            ) AS years
            
            LEFT JOIN result ON result.year = years.year AND result.breedId=:breedId AND result.districtId IN (
                    SELECT DISTINCT child.id FROM district AS parent
                        LEFT JOIN district AS child ON child.parentId = parent.id OR child.id = parent.id
                    WHERE parent.id=:districtId OR parent.parentId=:districtId                                          
                )
                        
            GROUP BY years.year
            ORDER BY years.year
        " );

        return Query::selectArray( $stmt, $args );
    }
    public static function yearsForSection( $districtId, $sectionId ) {
        $startYear = START_YEAR;
        $args = get_defined_vars();
        $stmt = Query::prepare( "            
            SELECT years.year,
                COUNT(*) AS count, :districtId AS districtId,
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                
                SUM( layDames) AS layDames, 
#                SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs,
                SUM( IF( breeders AND layEggs, breeders * layEggs, NULL ) )  /  SUM( IF( breeders AND layEggs, breeders, NULL ) ) AS layEggs,
#                SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                SUM( IF( breeders AND layWeight, breeders * layWeight, NULL ) )  /  SUM( IF( breeders AND layEggs, breeders, NULL ) ) AS layWeight,
                
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, 
                CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, 
                CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
#                SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
                SUM( IF( showCount AND showScore, showCOunt * showScore, NULL ) )  /  SUM( IF( showCount AND showScore, showCount, NULL ) ) AS showScore

            
            FROM (
                SELECT @year:=YEAR( NOW() )+1
            ) AS init, (
                SELECT @year:=@year-1 AS `year`
                FROM color
                WHERE @YEAR > :startYear
            ) AS years
            
            LEFT JOIN (
            	SELECT result.*
            	FROM result
				LEFT JOIN breed ON breed.id=result.breedId
				WHERE result.districtId IN (
                    SELECT DISTINCT child.id FROM district AS parent
                        LEFT JOIN district AS child ON child.parentId=parent.id OR child.id=parent.id 
                    WHERE parent.id=:districtId OR parent.parentId=:districtId                                          
	            ) AND breed.sectionId IN (
                    SELECT DISTINCT child.id FROM section AS parent                                  # root could be 2, geflügel 
                        LEFT JOIN section AS child ON child.parentId=parent.id OR child.id=parent.id # and children and repeat parent 
                    WHERE parent.id=:sectionId OR parent.parentId=:sectionId                         # root and it's children
                )           
			) AS results ON results.year = years.year 
                
            GROUP BY years.year
            ORDER BY years.year
        " );

        return Query::selectArray( $stmt, $args );
    }

}