<?php

namespace App\Queries;

class User
{
    public static function get( int $userId ) : ? array {
        $args = [ 'userId'=>$userId ];
        $stmt = Query::prepare( '
            SELECT user.id, name, email, user.district, user.club
            FROM user
            WHERE user.id=:userId
        ' );
        return Query::select( $stmt, $args );
    }

    public static function getAll() : ? array {
        $stmt = Query::prepare( '
            SELECT user.id, name, email, user.district 
            FROM user
        ' );
        return Query::selectArray( $stmt );
    }


    public static function login( string $email, string $password ) : ? array {
        $user = User::getVerifiedUser( $email, $password );
        if( $user ) {
            $user['moderator'] = User::getModerator( $user['id'] ); // array
            $user['isAdmin'] = User::isAdmin( $user['id'] );
            return $user;
        }
        return null;

    }

    public static function getResults( int $userId ) {
        $args = [ 'userId'=>$userId ];
        $stmt = Query::prepare( '
            SELECT COUNT(*) AS count, breeder, year, result.breed, color, std_breed.name AS breedName, std_color.name AS colorName,
                SUM( lay_dames ) AS lay_dames, 
                SUM( lay_dames * lay_eggs ) / SUM( lay_dames ) AS lay_eggs, 
                SUM( lay_dames * lay_weight ) / SUM( lay_dames ) AS lay_weight,
                SUM( brood_eggs ) AS brood_eggs,
                SUM( brood_fertile ) AS brood_fertile,
                SUM( brood_hatched ) AS brood_hatched,
                SUM( show_animals ) AS show_animals,
                SUM( show_animals * show_score ) / SUM( show_animals ) AS show_score
            FROM result
            LEFT JOIN std_breed ON std_breed.id = result.breed
            LEFT JOIN std_color ON std_color.id = result.color
            WHERE breeder=:userId
            GROUP BY year, breed, color
            ORDER BY year, breed, color   
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function getYears( int $userId ) {
        $args = [ 'userId'=>$userId ];
        $stmt = Query::prepare( '
            SELECT COUNT(*) AS count, breeder, year,
                SUM( lay_dames ) AS lay_dames, 
                SUM( lay_dames * lay_eggs ) / SUM( lay_dames ) AS lay_eggs, 
                SUM( lay_dames * lay_weight ) / SUM( lay_dames ) AS lay_weight,
                SUM( brood_eggs ) AS brood_eggs,
                SUM( brood_fertile ) AS brood_fertile,
                SUM( brood_hatched ) AS brood_hatched,
                SUM( show_animals ) AS show_animals,
                SUM( show_animals * show_score ) / SUM( show_animals ) AS show_score
            FROM result
            WHERE breeder=:userId
            GROUP BY year
            ORDER BY year   
        ' );
        return Query::selectArray( $stmt, $args );
    }




    private static function getVerifiedUser(string $email, string $password ) : ? array {
        $args = [ 'email'=>$email ];
        $stmt = Query::prepare( 'SELECT id, name, hash, email, district FROM user WHERE email=:email' );
        $user = Query::select( $stmt, $args );

        if( $user && password_verify( $password, $user['hash'] ) ) {
            unset( $user['hash'] );
            return $user;
        }
        return null;
    }

    private static function getModerator( int $id ) : array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( 'SELECT district FROM moderator WHERE id=:id' );
        $districts = Query::selectArray( $stmt, $args );
        return array_column( $districts, 'district' ); // only list of district id's needed
    }

    private static function isAdmin( int $id ) : bool {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( 'SELECT id FROM admin WHERE id=:id' );
        return Query::select($stmt, $args ) != null;

    }
}