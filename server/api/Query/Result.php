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
        $reportId, $districtId, $year, $group,
        $breedId, $colorId,
        $breeders, $pairs,
        $layDames, $layEggs, $layWeight,
        $broodEggs, $broodFertile, $broodHatched,
        $showCount, $showScore,
        $modifier
    ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO result ( reportId, districtId, `year`, `group`, breedId, colorId, breeders, pairs, layDames, layEggs, layWeight, broodEggs, broodFertile, broodHatched, showCount, showScore, modifier ) 
            VALUES ( :reportId, :districtId, :year, :group, :breedId, :colorId, :breeders, :pairs, :layDames, :layEggs, :layWeight, :broodEggs, :broodFertile, :broodHatched, :showCount, :showScore, :modifier )
        ' );
        return Query::insert( $stmt, $args );
    }
    public static function set(
        $id, $reportId, $districtId, $year, $group,
        $breedId, $colorId,
        $breeders, $pairs,
        $layDames, $layEggs, $layWeight,
        $broodEggs, $broodFertile, $broodHatched,
        $showCount, $showScore,
        $modifier
    ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE  result
            SET reportId=:reportId, districtId=:districtId, `year`=:year, `group`=:group, breedId=:breedId, colorId=:colorId, breeders=:breeders, pairs=:pairs, layDames=:layDames, layEggs=:layEggs, layWeight=:layWeight, broodEggs=:broodEggs, broodFertile=:broodFertile, broodHatched=:broodHatched, showCount=:showCount, showScore=:showScore, modifier=:modifier
            WHERE id=:id
        ' );
        return Query::update( $stmt, $args );
    }

    public static function del($id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM result
            WHERE id=:id
        ' );
        return Query::del( $stmt, $args );
    }

    public static function districtsForColor( $year, $colorId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( " 
            SELECT 
                COUNT(*) AS `count`, results.colorId, 
                district.id, district.name, district.latitude, district.longitude,
                
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                
                SUM( layDames) AS layDames, 
                SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs,
                SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, 
                CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, 
                CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
                SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
            FROM district
            LEFT JOIN (
                SELECT  result.*, district.rootId 
                FROM result 
                    LEFT JOIN district ON district.id=result.districtId
                WHERE `year`=:year AND colorId=:colorId               
            ) AS results ON results.rootId=district.id
            GROUP BY district.rootId
        " );
        return Query::selectArray( $stmt, $args );
    }
    public static function districtsForBreed( $year, $breedId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( " 
            SELECT 
                COUNT(*) AS `count`, results.colorId, 
                district.id, district.name, district.latitude, district.longitude,
                
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                
                SUM( layDames) AS layDames, 
                SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs,
                SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, 
                CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, 
                CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
                SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
            FROM district
            LEFT JOIN (
                SELECT  result.*, district.rootId 
                FROM result 
                    LEFT JOIN district ON district.id=result.districtId
                WHERE `year`=:year AND breedId=:breedId               
            ) AS results ON results.rootId=district.id
            GROUP BY district.rootId
        " );

        return Query::selectArray( $stmt, $args );
    }

    public static function districtsForSection( $year, $sectionId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT 
                COUNT(*) AS `count`, 
                district.rootId AS id, district.name, district.latitude, district.longitude, avg( results.year),
            
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                SUM( layDames) AS layDames, 
                SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs, SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore            
            FROM district 
            
            LEFT JOIN (
                SELECT breed.sectionId, result.*
                FROM result 
                    LEFT JOIN breed ON breed.id = result.breedId	
                WHERE result.year=:year
            ) AS results ON results.districtId = district.id AND results.sectionId IN (
                SELECT DISTINCT child.id FROM section AS parent
                    LEFT JOIN section AS child ON child.id = parent.id OR child.parentId = parent.id
                WHERE parent.id=:sectionId OR parent.parentId = :sectionId  
            )
            GROUP BY district.rootId      
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
                SUM( layDames) AS layDames, SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs, SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
            
            FROM (
                SELECT @year:=YEAR( NOW() )+1
            ) AS init, (
                SELECT @year:=@year-1 AS `year`
                FROM color
                WHERE @year > :startYear
            ) AS years
                LEFT JOIN result ON result.year = years.year AND result.colorId=:colorId AND result.districtId IN (
                    SELECT DISTINCT child.id FROM district AS parent
                        LEFT JOIN district AS child ON child.id = parent.id OR child.parentId = parent.id
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
                SUM( layDames) AS layDames, SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs, SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
            FROM (
                SELECT @year:=YEAR( NOW() )+1
            ) AS init, (
                SELECT @year:=@year-1 AS `year`
                FROM color
                WHERE @year > :startYear
            ) AS years
            
            LEFT JOIN result ON result.year = years.year AND result.breedId=:breedId AND result.districtId IN (
                    SELECT DISTINCT child.id FROM district AS parent
                        LEFT JOIN district AS child ON child.id = parent.id OR child.parentId = parent.id
                    WHERE parent.id=:districtId OR parent.parentId = :districtId                                          
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
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                SUM( layDames) AS layDames, SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs, SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
            
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
                    SELECT DISTINCT grandchild.id FROM section # root could be 1 
                        LEFT JOIN section AS child ON child.parentId=section.id OR child.id=section.id
                        LEFT JOIN section AS grandchild ON grandchild.parentId=child.id OR grandchild.id=section.id
                    WHERE section.id=1 OR section.parentId=1                                       
                )           
			) AS results ON results.year = years.year 
                
            GROUP BY years.year
            ORDER BY years.year
        " );

        return Query::selectArray( $stmt, $args );
    }

}