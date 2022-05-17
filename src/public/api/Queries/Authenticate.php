<?php

namespace App\Queries;

class Authenticate extends Query
{
    public function execute( $email, $password ) : ? array {
        $user = $this->getUser( $email, $password );
        if( $user ) {
            $user['moderates'] = $this->getModerates( $user['id'] );
            $user['isAdmin'] = $this->isAdmin( $user['id'] );
            return $user;
        }
        return null;
    }

    private function getUser( $email, $password ) {
        $args = [ 'email'=>$email ];
        $query = 'SELECT id, name, hash, email FROM user WHERE email=:email';
        $user = $this->get( $query, $args );
        if( $user && password_verify( $password, $user['hash'] ) ) {
            unset( $user['hash'] );
            return $user;
        }
        return null;
    }

    private function getModerates( $id ) : array {
        $args = [ 'id'=>$id ];
        $query = 'SELECT district FROM moderator WHERE id=:id';
        $districts = $this->getArray($query, $args );
        return array_column( $districts, 'district' );
    }

    private function isAdmin( $id ) : bool {
        $args = [ 'id'=>$id ];
        $query = 'SELECT id FROM admin WHERE id=:id';
        return $this->get($query, $args ) != null;

    }
}