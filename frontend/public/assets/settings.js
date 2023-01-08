// use for caching network gets

function generateYears() {
    const thisYear = new Date().getFullYear();
    const years = [];
    for( let year=thisYear; year>=2000; year-- ) {
        years.push( year );
    }
    return years;
}

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

MAXLINESPERPAGE = 2;
