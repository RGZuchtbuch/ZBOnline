<?php
namespace App;

class Config
{
    const DB_HOST = 'localhost';
    const DB_NAME = 'zbo';
    const DB_USER = 'zbo';
    const DB_PASSWORD = '^02e4YPZ75CP%cbmxz7#hcMC4jRy@yx3';

    const TOKEN_SECRET = 'jhfvj5v6898v^&*&^T(^BB&^t97b97crvR%(&^%er86976(&V'; // for de/encoding credentials
    const TOKEN_ALGORITHM = 'HS256';
}

/*
 <?php

return [
    'db' => [
        'host' => 'localhost',
        'name' => 'zbo',
        'user' => 'zbo',
        'password' => '^02e4YPZ75CP%cbmxz7#hcMC4jRy@yx3'
    ],
    'credentials'=>[
        //'secret'=>'FIytfviYTVIYftiyTFIYTFviyftv5r86856V^*5rV*^%r'
        'secret' => 'jhfvj5v6898v^&*&^T(^BB&^t97b97crvR%(&^%er86976(&V'
    ]
];
//
 */