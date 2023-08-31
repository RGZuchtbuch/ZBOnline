<?php

namespace App\query;

class Result extends Query
{
    public static function get( $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, pairId, districtId, `year`, `group`, breedId, colorId, breeders, pairs, layDames, layEggs, broodEggs, broodFertile, broodHatched, showCount, showScore
            FROM result
            WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function new(
        ? int $pairId, int $districtId, int $year, string $group,
        int $breedId, ? int $colorId,
        int $breeders, ? int $pairs,
        ? int $layDames, ? float $layEggs, ? float $layWeight,
        ? int $broodEggs, ? int $broodFertile, ? int $broodHatched,
        ? int $showCount, ? float $showScore,
        int $modifierId
    ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO result ( pairId, districtId, `year`, `group`, breedId, colorId, breeders, pairs, layDames, layEggs, layWeight, broodEggs, broodFertile, broodHatched, showCount, showScore, modifierId ) 
            VALUES ( :pairId, :districtId, :year, :group, :breedId, :colorId, :breeders, :pairs, :layDames, :layEggs, :layWeight, :broodEggs, :broodFertile, :broodHatched, :showCount, :showScore, :modifierId )
        ' );
        return Query::insert( $stmt, $args );
    }


    public static function set(
        int $id, ? int $pairId, int $districtId, int $year, string $group,
        int $breedId, ? int $colorId,
        int $breeders, ? int $pairs,
        ? int $layDames, ? float $layEggs, ? float $layWeight,
        ? int $broodEggs, ? int $broodFertile, ? int $broodHatched,
        ? int $showCount, ? float $showScore,
        int $modifierId
    ) : bool {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE  result
            SET pairId=:pairId, districtId=:districtId, `year`=:year, `group`=:group, breedId=:breedId, colorId=:colorId, breeders=:breeders, pairs=:pairs, layDames=:layDames, layEggs=:layEggs, layWeight=:layWeight, broodEggs=:broodEggs, broodFertile=:broodFertile, broodHatched=:broodHatched, showCount=:showCount, showScore=:showScore, modifierId=:modifierId
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
                COUNT(*) AS `count`,
                district.rootId AS id, district.name, district.latitude, district.longitude,
            
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, 
                CAST( SUM( IF( layer AND breeders, breeders, NULL ) ) AS UNSIGNED ) AS layerBreeders,
				CAST( SUM( IF( NOT layer AND breeders, breeders, NULL ) ) AS UNSIGNED ) AS nonLayerBreeders,
                
                CAST( SUM( pairs ) AS UNSIGNED) AS pairs, 
                CAST( SUM( IF( layer AND pairs, pairs, NULL ) ) AS UNSIGNED ) AS layerPairs,
				CAST( SUM( IF( NOT layer AND pairs, pairs, NULL ) ) AS UNSIGNED ) AS nonLayerPairs,	       
				
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, 
                CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors,
                
                CAST( SUM( IF( layer, layDames, NULL ) ) AS UNSIGNED ) AS layDames,
                # note agv by taking ( breeders * leyEggs ) / breeders !
                CAST( SUM( IF( layer AND breeders AND layEggs, breeders * layEggs, NULL ) )  /  SUM( IF( layer AND breeders AND layEggs, breeders, NULL ) ) AS DOUBLE ) AS layEggs,
                CAST( SUM( IF( layer AND breeders AND layWeight, breeders * layWeight, NULL ) )  /  SUM( IF( layer AND breeders AND layEggs, breeders, NULL ) ) AS DOUBLE ) AS layWeight,                             			
            
                # layers
                CAST( SUM( IF( layer, broodEggs, NULL ) ) AS UNSIGNED ) AS broodEggs,
                CAST( SUM( IF( layer, broodFertile, NULL ) ) AS UNSIGNED ) AS broodFertile,
                CAST( SUM( IF( layer, broodHatched, NULL ) ) AS UNSIGNED ) AS broodHatched,        
                # pigeons
                CAST( SUM( IF( NOT layer AND pairs, broodHatched, NULL ) ) AS UNSIGNED ) AS chicks,               
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
                CAST( SUM( IF( showCount AND showScore, showCOunt * showScore, NULL ) )  /  SUM( IF( showCount AND showScore, showCount, NULL ) ) AS DOUBLE ) AS showScore
            
            FROM district
                
            LEFT JOIN (
                SELECT result.*, section.layers AS layer
                FROM result
                LEFT JOIN breed ON breed.id = result.breedId
                LEFT JOIN section ON section.id = breed.sectionId
                
                WHERE year=:year AND colorId=:colorId
            ) AS results ON results.districtId = district.id                    
            
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
                COUNT(*) AS `count`, 
                district.rootId AS id, district.name, district.latitude, district.longitude,
            
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, 
                CAST( SUM( IF( layer AND breeders, breeders, NULL ) ) AS UNSIGNED ) AS layerBreeders,
				CAST( SUM( IF( NOT layer AND breeders, breeders, NULL ) ) AS UNSIGNED ) AS nonLayerBreeders,
                
                CAST( SUM( pairs ) AS UNSIGNED) AS pairs, 
                CAST( SUM( IF( layer AND pairs, pairs, NULL ) ) AS UNSIGNED ) AS layerPairs,
				CAST( SUM( IF( NOT layer AND pairs, pairs, NULL ) ) AS UNSIGNED ) AS nonLayerPairs,	       
				
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, 
                CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors,
                
                CAST( SUM( IF( layer, layDames, NULL ) ) AS UNSIGNED ) AS layDames,
                # note agv by taking ( breeders * leyEggs ) / breeders !
                CAST( SUM( IF( layer AND breeders AND layEggs, breeders * layEggs, NULL ) )  /  SUM( IF( layer AND breeders AND layEggs, breeders, NULL ) ) AS DOUBLE ) AS layEggs,
                CAST( SUM( IF( layer AND breeders AND layWeight, breeders * layWeight, NULL ) )  /  SUM( IF( layer AND breeders AND layEggs, breeders, NULL ) ) AS DOUBLE ) AS layWeight,                             			
            
                # layers
                CAST( SUM( IF( layer, broodEggs, NULL ) ) AS UNSIGNED ) AS broodEggs,
                CAST( SUM( IF( layer, broodFertile, NULL ) ) AS UNSIGNED ) AS broodFertile,
                CAST( SUM( IF( layer, broodHatched, NULL ) ) AS UNSIGNED ) AS broodHatched,        
                # pigeons
                CAST( SUM( IF( NOT layer AND pairs, broodHatched, NULL ) ) AS UNSIGNED ) AS chicks,                           
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
                CAST( SUM( IF( showCount AND showScore, showCOunt * showScore, NULL ) )  /  SUM( IF( showCount AND showScore, showCount, NULL ) ) AS DOUBLE ) AS showScore

            FROM district # all                
                
            LEFT JOIN (
                SELECT result.*, section.layers AS layer
                FROM result
                LEFT JOIN breed ON breed.id = result.breedId
                LEFT JOIN section ON section.id = breed.sectionId
                
                WHERE `year`=:year AND breedId=:breedId
            ) AS results ON results.districtId = district.id                  

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
                district.rootId AS id, district.name, district.latitude, district.longitude,
            
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, 
                CAST( SUM( IF( layer AND breeders, breeders, NULL ) ) AS UNSIGNED ) AS layerBreeders,
				CAST( SUM( IF( NOT layer AND breeders, breeders, NULL ) ) AS UNSIGNED ) AS nonLayerBreeders,
                
                CAST( SUM( pairs ) AS UNSIGNED) AS pairs, 
                CAST( SUM( IF( layer AND pairs, pairs, NULL ) ) AS UNSIGNED ) AS layerPairs,
				CAST( SUM( IF( NOT layer AND pairs, pairs, NULL ) ) AS UNSIGNED ) AS nonLayerPairs,	       
				
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, 
                CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors,
                
                CAST( SUM( IF( layer, layDames, NULL ) ) AS UNSIGNED ) AS layDames,
                # note agv by taking ( breeders * leyEggs ) / breeders !
                CAST( SUM( IF( layer AND breeders AND layEggs, breeders * layEggs, NULL ) )  /  SUM( IF( layer AND breeders AND layEggs, breeders, NULL ) ) AS DOUBLE ) AS layEggs,
                CAST( SUM( IF( layer AND breeders AND layWeight, breeders * layWeight, NULL ) )  /  SUM( IF( layer AND breeders AND layEggs, breeders, NULL ) ) AS DOUBLE ) AS layWeight,                             			
            
                # layers
                CAST( SUM( IF( layer, broodEggs, NULL ) ) AS UNSIGNED ) AS broodEggs,
                CAST( SUM( IF( layer, broodFertile, NULL ) ) AS UNSIGNED ) AS broodFertile,
                CAST( SUM( IF( layer, broodHatched, NULL ) ) AS UNSIGNED ) AS broodHatched,        
                # pigeons
                CAST( SUM( IF( NOT layer AND pairs, broodHatched, NULL ) ) AS UNSIGNED ) AS chicks,               
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
                CAST( SUM( IF( showCount AND showScore, showCOunt * showScore, NULL ) )  /  SUM( IF( showCount AND showScore, showCount, NULL ) ) AS DOUBLE ) AS showScore
            
            FROM district # all
                
            LEFT JOIN (
                SELECT breed.sectionId, result.*, section.layers AS layer
                FROM result 
                LEFT JOIN breed ON breed.id = result.breedId
                LEFT JOIN section ON section.id = breed.sectionId
            	
				WHERE 
				    year=:year AND 
				    breed.sectionId IN (
                        SELECT DISTINCT child.id FROM section AS parent                                  # root could be 2, geflügel 
                            LEFT JOIN section AS child ON child.parentId=parent.id OR child.id=parent.id # and children and repeat parent 
                        WHERE parent.id=:sectionId OR parent.parentId=:sectionId                         # root and it's children
                    )           
			) AS results ON results.districtId = district.id                 
            

            GROUP BY district.rootId # LV's     
            ORDER BY district.rootId
        " );

        return Query::selectArray($stmt, $args );
    }


    public static function yearsForColor( $districtId, $colorId ) {
        $startYear = START_YEAR;
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT years.year,
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, 
                CAST( SUM( IF( layer AND breeders, breeders, NULL ) ) AS UNSIGNED ) AS layerBreeders,
				CAST( SUM( IF( NOT layer AND breeders, breeders, NULL ) ) AS UNSIGNED ) AS nonLayerBreeders,
                
                CAST( SUM( pairs ) AS UNSIGNED) AS pairs, 
                CAST( SUM( IF( layer AND pairs, pairs, NULL ) ) AS UNSIGNED ) AS layerPairs,
				CAST( SUM( IF( NOT layer AND pairs, pairs, NULL ) ) AS UNSIGNED ) AS nonLayerPairs,	       
				
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, 
                CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors,
                
                CAST( SUM( IF( layer, layDames, NULL ) ) AS UNSIGNED ) AS layDames,
                # note agv by taking ( breeders * leyEggs ) / breeders !
                CAST( SUM( IF( layer AND breeders AND layEggs, breeders * layEggs, NULL ) )  /  SUM( IF( layer AND breeders AND layEggs, breeders, NULL ) ) AS DOUBLE ) AS layEggs,
                CAST( SUM( IF( layer AND breeders AND layWeight, breeders * layWeight, NULL ) )  /  SUM( IF( layer AND breeders AND layEggs, breeders, NULL ) ) AS DOUBLE ) AS layWeight,                             			
            
                # layers
                CAST( SUM( IF( layer, broodEggs, NULL ) ) AS UNSIGNED ) AS broodEggs,
                CAST( SUM( IF( layer, broodFertile, NULL ) ) AS UNSIGNED ) AS broodFertile,
                CAST( SUM( IF( layer, broodHatched, NULL ) ) AS UNSIGNED ) AS broodHatched,        
                # pigeons
                CAST( SUM( IF( NOT layer AND pairs, broodHatched, NULL ) ) AS UNSIGNED ) AS chicks,               
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
                CAST( SUM( IF( showCount AND showScore, showCOunt * showScore, NULL ) )  /  SUM( IF( showCount AND showScore, showCount, NULL ) ) AS DOUBLE ) AS showScore

            
            FROM (
                SELECT @year:=YEAR( NOW() )+1
            ) AS init, (
                SELECT @year:=@year-1 AS `year`
                FROM color
                WHERE @year > :startYear
            ) AS years
                
            LEFT JOIN (
                SELECT result.*, section.layers AS layer
                FROM result
                LEFT JOIN breed ON breed.id = result.breedId
                LEFT JOIN section ON section.id = breed.sectionId
                WHERE result.districtId IN ( # nested districts
                    SELECT DISTINCT child.id FROM district AS parent
                        LEFT JOIN district AS child ON child.parentId = parent.id OR child.id = parent.id
                    WHERE parent.id=:districtId OR parent.parentId=:districtId                     
                )
            ) AS results ON results.year = years.year AND results.colorId=:colorId                   
                                        
            GROUP BY years.year
            ORDER BY years.year
        " );

        return Query::selectArray( $stmt, $args );
    }

    public static function yearsForBreed( $districtId, $breedId ) {
        $startYear = START_YEAR;
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT years.year,
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, 
                CAST( SUM( IF( layer AND breeders, breeders, NULL ) ) AS UNSIGNED ) AS layerBreeders,
				CAST( SUM( IF( NOT layer AND breeders, breeders, NULL ) ) AS UNSIGNED ) AS nonLayerBreeders,
                
                CAST( SUM( pairs ) AS UNSIGNED) AS pairs, 
                CAST( SUM( IF( layer AND pairs, pairs, NULL ) ) AS UNSIGNED ) AS layerPairs,
				CAST( SUM( IF( NOT layer AND pairs, pairs, NULL ) ) AS UNSIGNED ) AS nonLayerPairs,	       
				
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, 
                CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors,
                
                CAST( SUM( IF( layer, layDames, NULL ) ) AS UNSIGNED ) AS layDames,
                # note agv by taking ( breeders * leyEggs ) / breeders !
                CAST( SUM( IF( layer AND breeders AND layEggs, breeders * layEggs, NULL ) )  /  SUM( IF( layer AND breeders AND layEggs, breeders, NULL ) ) AS DOUBLE ) AS layEggs,
                CAST( SUM( IF( layer AND breeders AND layWeight, breeders * layWeight, NULL ) )  /  SUM( IF( layer AND breeders AND layEggs, breeders, NULL ) ) AS DOUBLE ) AS layWeight,                             			
            
                # layers
                CAST( SUM( IF( layer, broodEggs, NULL ) ) AS UNSIGNED ) AS broodEggs,
                CAST( SUM( IF( layer, broodFertile, NULL ) ) AS UNSIGNED ) AS broodFertile,
                CAST( SUM( IF( layer, broodHatched, NULL ) ) AS UNSIGNED ) AS broodHatched,        
                # pigeons
                CAST( SUM( IF( NOT layer AND pairs, broodHatched, NULL ) ) AS UNSIGNED ) AS chicks,               
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
                CAST( SUM( IF( showCount AND showScore, showCOunt * showScore, NULL ) )  /  SUM( IF( showCount AND showScore, showCount, NULL ) ) AS DOUBLE ) AS showScore

            FROM (
                SELECT @year:=YEAR( NOW() )+1
            ) AS init, (
                SELECT @year:=@year-1 AS `year`
                FROM color
                WHERE @year > :startYear
            ) AS years
            
            LEFT JOIN (
                SELECT result.*, section.layers AS layer
                FROM result
                LEFT JOIN breed ON breed.id = result.breedId
                LEFT JOIN section ON section.id = breed.sectionId
                WHERE result.districtId IN ( # nested districts
                    SELECT DISTINCT child.id FROM district AS parent
                        LEFT JOIN district AS child ON child.parentId = parent.id OR child.id = parent.id
                    WHERE parent.id=:districtId OR parent.parentId=:districtId                     
                )
            ) AS results ON results.year = years.year AND results.breedId=:breedId  
                        
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
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, 
                CAST( SUM( IF( layer AND breeders, breeders, NULL ) ) AS UNSIGNED ) AS layerBreeders,
				CAST( SUM( IF( NOT layer AND breeders, breeders, NULL ) ) AS UNSIGNED ) AS nonLayerBreeders,
                
                CAST( SUM( pairs ) AS UNSIGNED) AS pairs, 
                CAST( SUM( IF( layer AND pairs, pairs, NULL ) ) AS UNSIGNED ) AS layerPairs,
				CAST( SUM( IF( NOT layer AND pairs, pairs, NULL ) ) AS UNSIGNED ) AS nonLayerPairs,	       
				
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, 
                CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors,
                
                CAST( SUM( IF( layer, layDames, NULL ) ) AS UNSIGNED ) AS layDames,
                # note agv by taking ( breeders * leyEggs ) / breeders !
                CAST( SUM( IF( layer AND breeders AND layEggs, breeders * layEggs, NULL ) )  /  SUM( IF( layer AND breeders AND layEggs, breeders, NULL ) ) AS DOUBLE ) AS layEggs,
                CAST( SUM( IF( layer AND breeders AND layWeight, breeders * layWeight, NULL ) )  /  SUM( IF( layer AND breeders AND layEggs, breeders, NULL ) ) AS DOUBLE ) AS layWeight,                             			
            
                # layers
                CAST( SUM( IF( layer, broodEggs, NULL ) ) AS UNSIGNED ) AS broodEggs,
                CAST( SUM( IF( layer, broodFertile, NULL ) ) AS UNSIGNED ) AS broodFertile,
                CAST( SUM( IF( layer, broodHatched, NULL ) ) AS UNSIGNED ) AS broodHatched,        
                # pigeons
                CAST( SUM( IF( NOT layer AND pairs, broodHatched, NULL ) ) AS UNSIGNED ) AS chicks,               
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
                CAST( SUM( IF( showCount AND showScore, showCOunt * showScore, NULL ) )  /  SUM( IF( showCount AND showScore, showCount, NULL ) ) AS DOUBLE ) AS showScore

            
            FROM (
                SELECT @year:=YEAR( NOW() )+1
            ) AS init, (
                SELECT @year:=@year-1 AS `year`
                FROM color
                WHERE @YEAR > :startYear
            ) AS years
            
            LEFT JOIN (
            	SELECT result.*, section.layers AS layer
            	FROM result
				LEFT JOIN breed ON breed.id=result.breedId
            	LEFT JOIN section ON section.id = breed.sectionId
            	
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

    public static function delForPair(int $pairId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM result
            WHERE pairId=:pairId
        ' );
        return Query::delete( $stmt, $args );
    }

}