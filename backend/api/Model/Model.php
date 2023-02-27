<?php

namespace App\Model;

abstract class Model
{
    public abstract static function authorized( int $requesterId, int $id ) : bool; // enforce implementation !

}