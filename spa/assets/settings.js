// use for caching network gets



console.log( 'HostName', location.hostname );

const WORKINPROGRESS = false; // use in case of work in progress and disable the website temporary.

// switches from dev to production
const APIROOT = location.hostname === 'localhost' || location.hostname === '127.0.0.1' ? 'http://localhost:80/' : 'https://rgzuchtbuch.de/';

const CACHETIMEOUT = 60_000; // 60 secs in dev mode
const CACHECHECKINTERVAL = 300_000; // once a minute

const MINDATE = '1850-01-01';
const MAXDATE = (new Date().getFullYear()+1) + '-11-31';

const MINYEAR = 1850;
const MAXYEAR = new Date().getFullYear() + 1;

const STARTYEAR = 2000;

const MAXLAYDAMES = 99;
const MAXLAYEGGS = 366;
const MINLAYWEIGHT = 5;
const MAXLAYWEIGHT = 99;

const MAXBROODEGGS = 9999;

const MAXSHOWCOUNT = 999;
const MINSHOWSCORE = 90;
const MAXSHOWSCORE = 97;

const MAXLINESPERPAGE = 2;

const MINLATITUDE = 45;
const MAXLATITUDE = 55;
const MINLONGITUDE = 5;
const MAXLONGITUDE = 15;

const MAXBUBBLE = 35;

const LAYERS = 4;
const PIGEONS = 5; // section id

const STANDARDENABLED = true;
const RESULTSENABLED = true;
const BREEDERENABLED = false;
const MODERATORENABLED = true;
const ADMINENABLED = true;
