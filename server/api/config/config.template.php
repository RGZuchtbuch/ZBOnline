<?php
// should never be in the repo !

const DB_HOST = 'localhost:3306'; // 3306 for deploy
const DB_NAME = ''; // database name
const DB_USER = ''; // db user name
const DB_PASSWORD = ''; // db user password

//https://web-service4u.de/faq/67-wie-lauten-meine-zugangsdaten-fuer-email?catid=51%3Aemail
const MAIL_SERVER      = ''; // your mail server
const MAIL_PORT        = 465; // mailserver port
const MAIL_SENDER      = 'obmann@rgzuchtbuch.de'; // default mail sender
const MAIL_SENDER_NAME = 'Obmann';
const MAIL_PASSWORD    = ''; // mailserver password

const TOKEN_EXPIRE = 1440; // expire time in minutes
const TOKEN_SECRET = ''; // servers jwt secret
const TOKEN_ALGORITHM = 'HS256'; // used for jwt

const START_YEAR = 2000; // starting year of recording data, for graphs and selects

