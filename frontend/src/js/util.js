
/*
function extendYear( year, max ) {
    if( year >=0 && year < 100 ) {
        const maxYear = max.getFullYear() % 100;
        if( year > maxYear ) {
            return max.getFullYear() - 100 - maxYear + year;
        } else {
            return max.getFullYear() - maxYear + year;
        }
    }
    return year;
}
*/

export function toDate( input, min, max ) {
    if( input && max ) {
        min = new Date(min);
        max = new Date(max);

        let date = null;
        let match =
            input.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\.](1[0-2]|0[1-9]|[1-9])[\.]([0-9]{2})$/) ||  // 31.01.22 D
            input.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\-](1[0-2]|0[1-9]|[1-9])[\-]([0-9]{2})$/);    // 31-01-22 NL
        if (match) {
            console.log('Match D', match);
            let year = Number(match[3]);
            let maxYear = max.getFullYear() % 100; // year in century
            year = max.getFullYear() - maxYear + year - (year <= maxYear ? 0 : 100);
            date = new Date(year, match[2] - 1, match[1]);
        } else {
            match =
                input.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\.](1[0-2]|0[1-9]|[1-9])[\.]([0-9]{4})$/) ||      // 31.01.2022 D
                input.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\-](1[0-2]|0[1-9]|[1-9])[\-]([0-9]{4})$/);        // 31-01-2022 NL
            if (match) {
                date = new Date(match[3], match[2]-1, match[1]);
            } else {
                match =
                    input.match(/^([0-9]{4})[\-\.](1[0-2]|0[1-9]|[1-9])[\-\.](3[0-1]|[12][0-9]|0[1-9]|[1-9])$/);    // 2022-7-22 ISO
                if (match) {
                    date = new Date(match[1], match[2]-1, match[3]);
                }
            }
        }
        console.log('Date ', date, min, max);
        if (date && date >= min && date <= max) {
            return date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate(); // iso
        }
    }
    return null;
}

export function formatDate(local, dateString ) { // local ignored for now, default to 'D'
    if( dateString ) {
        const date = new Date( dateString );
        switch( local ) {
            case 'D': return date.getDate()+'.'+(date.getMonth()+1)+'.'+date.getFullYear(); // D
            default:  return date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate(); // ISO
        }
    }
    return null;
}




export function getProduction( days, eggs, dames ) {
    let fit;
    if( days > 365 ) {
        fit = 365;
    } else if( days > 183 ) {
        fit = 340.1984 - 0.7930455*days + 0.002358891*days*days;;  // https://mycurvefit.com/
    } else {
        fit = 274;
    }
    const production = eggs/dames * fit/days; //fit * eggs/days/dames;
    if( production >= 0 && production <= 366 ) {
        console.log( 'P', production );
        return production;
    }
    return null;
}

export function printDate( date ) {
    if( date ) {
        date = new Date(date);
        return date.getDate() + '.' + (date.getMonth() + 1) + '.' + date.getFullYear();
    }
    return null;
}

export function printPct( value, decimals = 0 ) {
    return (value*100).toFixed( decimals )+'%';
}

export function dec( a ) {
    if( a != null ) {
        return a;
    }
    return '-';
}

export function perc( a, b, dec=1 ) {
    if( a != null && b != null ) {
        return (100 * a / b).toFixed( dec )+'%';
    }
    return '-';
}