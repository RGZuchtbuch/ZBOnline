// use for caching network gets

function generateYears() {
    const thisYear = new Date().getFullYear();
    const years = [];
    for( let year=thisYear; year>=2000; year-- ) {
        years.push( year );
    }
    return years;
}

console.log( 'HostName', location.hostname );
const settings = {
    api : {
        root: location.hostname === 'localhost' || location.hostname === '127.0.0.1' ? 'http://localhost:80/' : 'https://rgzuchtbuch.de/',
    },
    cache : {
        TIMEOUT : 5000 // should be 30 mins = 30 * 60000 = 1800000
    },
    date : {
        min: '1850-01-01',
        max: (new Date().getFullYear()+1) + '-11-31',
    },
    select : {
        groups: ['I', 'II', 'III'], // for selects
        sections: [ // for selects
            {id: 3, name: 'Groß & Wassergeflügel'},
            {id: 11, name: ' → Hühner (Groß)'}, { id: 12, name: ' → Zwerghühner' }, {id: 13, name: ' → Wachteln'}, {
            id: 5, name: 'Tauben'},
            {id: 6, name: 'Ziergeflügel'}
        ],
        years : generateYears(), //
    }
}



const MINYEAR = 1850;
const MAXYEAR = 2030;

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


const STANDARDENABLED = true;
const RESULTSENABLED = true;
const MYBOOKENABLED = false;
const MODERATORENABLED = true;
const ADMINENABLED = true;
