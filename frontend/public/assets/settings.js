// use for caching network gets
const settings = {
    api : {
        root: 'http://localhost:8888/',
    },
    cache : {
        TIMEOUT : 5000 // should be 30 mins = 30 * 60000 = 1800000
    },
    date : {
        min: '1850-01-01',
        max: (new Date().getFullYear()+1) + '-11-31',
    }

}

// consts for colors
MINYEAR = 1850;
MAXYEAR = 2030;

MAXLAYDAMES = 99;
MAXLAYEGGS = 366;
MINLAYWEIGHT = 5;
MAXLAYWEIGHT = 99;

MAXBROODEGGS = 9999;

MAXSHOWCOUNT = 999;
MINSHOWSCORE = 90;
MAXSHOWSCORE = 97;
