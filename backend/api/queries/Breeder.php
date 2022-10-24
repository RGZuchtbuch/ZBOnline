<?php

namespace App\Queries;

class Breeder
{
    public static function get( int $id ) : ? array {
        $args = [ 'userId'=>$id ];
        $stmt = Query::prepare( '
            SELECT user.id, name, email, user.districtId, user.clubId
            FROM user
            WHERE user.id=:userId
        ' );
        return Query::select( $stmt, $args );
    }

    public static function getAll() : ? array {
        $stmt = Query::prepare( '
            SELECT user.id, name, email, user.districtId 
            FROM user
        ' );
        return Query::selectArray( $stmt );
    }

    public static function getReports( int $userId ) {
		return []; // TODO
	}
	
    public static function getResults( int $userId ) {
        return []; //TODO
        $args = [ 'userId'=>$userId ];
        $stmt = Query::prepare( '
            SELECT COUNT(*) AS count, breederId, year, template.breedId, colorId, std_breed.name AS breedName, std_color.name AS colorName,
                SUM( lay_dames ) AS lay_dames, 
                SUM( lay_dames * lay_eggs ) / SUM( lay_dames ) AS lay_eggs, 
                SUM( lay_dames * lay_weight ) / SUM( lay_dames ) AS lay_weight,
                SUM( brood_eggs ) AS brood_eggs,
                SUM( brood_fertile ) AS brood_fertile,
                SUM( brood_hatched ) AS brood_hatched,
                SUM( show_count ) AS show_count,
                SUM( show_count * show_score ) / SUM( show_count ) AS show_score
            FROM template
            LEFT JOIN std_breed ON std_breed.id = template.breedId
            LEFT JOIN std_color ON std_color.id = template.colorId
            WHERE breederId=:userId
            GROUP BY year, std_breed.sectionId, breedName, colorName
            ORDER BY year, std_breed.sectionId, breedName, colorName   
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function getYears( int $userId ) {
        $args = [ 'userId'=>$userId ];
        $stmt = Query::prepare( '
            SELECT COUNT(*) AS count, breederId, year,
                SUM( lay_dames ) AS lay_dames, 
                SUM( lay_dames * lay_eggs ) / SUM( lay_dames ) AS lay_eggs, 
                SUM( lay_dames * lay_weight ) / SUM( lay_dames ) AS lay_weight,
                SUM( brood_eggs ) AS brood_eggs,
                SUM( brood_fertile ) AS brood_fertile,
                SUM( brood_hatched ) AS brood_hatched,
                SUM( show_count ) AS show_animals,
                SUM( show_count * show_score ) / SUM( show_count ) AS show_score
            FROM template
            WHERE breederId=:userId
            GROUP BY year
            ORDER BY year   
        ' );
        return Query::selectArray( $stmt, $args );
    }

}