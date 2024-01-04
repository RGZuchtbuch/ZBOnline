<?php

namespace App\controller\color\old;

use App\controller\Controller;
use App\query;

class Get extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $id = $this->args['id'];
        $color = query\Color::get( $id );
        return [ 'color'=>$color ];
    }
}

