<?php
namespace App;

class Config
{
    const DB_HOST = 'localhost:3306'; // 3306 for deploy
    const DB_NAME = 'web71db_zb';
    const DB_USER = 'web71db_zb';
    const DB_PASSWORD = 'jfb%^GR56rgf%&*^iUYHTgiuytgiuYCddyi%$^';

    //https://web-service4u.de/faq/67-wie-lauten-meine-zugangsdaten-fuer-email?catid=51%3Aemail
//    const MAIL_SERVER    = 'webclient3.webclient3.de';
    const MAIL_SERVER      = 'mail.webclient3.de';
    const MAIL_PORT        = 465;
    const MAIL_SENDER      = 'obmann@rgzuchtbuch.de';
    const MAIL_SENDER_NAME = 'Obmann';
    const MAIL_PASSWORD = 'JooujPabeuzads+';

    const TOKEN_EXPIRE = 1440;
    const TOKEN_SECRET = 'jhfvj5v6898v^&*&^T(^BB&^t97b97crvR%(&^%er86976(&V'; // for de/encoding credentials
    const TOKEN_ALGORITHM = 'HS256';

    const START_YEAR = 2000;
}
