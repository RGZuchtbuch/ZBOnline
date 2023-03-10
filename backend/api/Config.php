<?php
namespace App;

class Config
{
    const DB_HOST = 'localhost:3306'; // 3306 for deploy
    const DB_NAME = 'web71db_zb';
    const DB_USER = 'web71db_zb';
    const DB_PASSWORD = 'jfb%^GR56rgf%&*^iUYHTgiuytgiuYCddyi%$^';

    const TOKEN_EXPIRE = 1440;
    const TOKEN_SECRET = 'jhfvj5v6898v^&*&^T(^BB&^t97b97crvR%(&^%er86976(&V'; // for de/encoding credentials
    const TOKEN_ALGORITHM = 'HS256';

    const START_YEAR = 2000;
}
