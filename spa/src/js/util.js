import {isNumber} from "chart.js/helpers";

function mercY( lat ) {
    return Math.log( Math.tan( Math.PI/4 + lat/2 ) );
}

export function gpsToPx( width, height, west, east, south, north, lon, lat ) {

    west = west * Math.PI/180;
    east = east * Math.PI/180;
    south = south * Math.PI/180;
    north = north * Math.PI/180;

    lon = lon * Math.PI/180;
    lat = lat * Math.PI/180;

    const yMin = mercY( south );
    const yMax = mercY( north );
    const xFactor = width / ( east - west );
    const yFactor = height / ( yMax - yMin );

    let x = (lon-west)*xFactor;
    let y = (yMax - mercY( lat ) )*yFactor;
    return { x:x, y:y };
}


export function calcColor( min, max, value, alpha ) {
    const relValue = (value-min)/(max-min);
    const mid = (min+max)/2;
    let r = 15;
    let g = 15;
    if( relValue < 0.5 ) { // get color on scale 0.15
        g = Math.round( 15 * 2 * relValue );
    } else {
        r = Math.round( 15 * 2 * ( 1 - relValue ) );
    }
    alpha = Math.round( 15 * alpha ); // range 0..1 to 0..15
    return '#'+r.toString(16)+g.toString(16)+'0'+alpha.toString(16); // only 1 char per color, like '#48f7' making '#4488ff77'
}

export function isDate( value ) {
    let date = null;

    // try convert date to 2023-08-24 format, is success, we have a valid date.
    let match =
        value.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\.](1[0-2]|0[1-9]|[1-9])[\.]([0-9]{2})$/) ||  // 31.01.22 D
        value.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\-](1[0-2]|0[1-9]|[1-9])[\-]([0-9]{2})$/);    // 31-01-22 NL
    if (match) {
        let year = Number(match[3]); // needs to be extended to full year
        const currentYear = Date.now().getFullYear();
        let maxYear = ( max.getFullYear() + 1 ) % 100; // year in century
        year = currentYear - ( currentYear + 1 ) % 100 + year - (year <= maxYear ? 0 : 100);
        date = new Date(year, match[2] - 1, match[1]);
    } else {
        match =
            value.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\.](1[0-2]|0[1-9]|[1-9])[\.]([0-9]{4})$/) ||      // 31.01.2022 D
            value.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\-](1[0-2]|0[1-9]|[1-9])[\-]([0-9]{4})$/);        // 31-01-2022 NL
        if (match) {
            date = new Date(match[3], match[2]-1, match[1]);
        } else {
            match =
                value.match(/^([0-9]{4})[\-\.](1[0-2]|0[1-9]|[1-9])[\-\.](3[0-1]|[12][0-9]|0[1-9]|[1-9])$/);    // 2022-7-22 ISO
            if (match) {
                date = new Date(match[1], match[2]-1, match[3]);
            }
        }
    }
    return date && ! isNaN( date.valueOf() );

}

export function toDate( input, min, max ) {
    if( input && max ) {
        min = new Date(min);
        max = new Date(max);

        let date = null;
        let match =
            input.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\.](1[0-2]|0[1-9]|[1-9])[\.]([0-9]{2})$/) ||  // 31.01.22 D
            input.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\-](1[0-2]|0[1-9]|[1-9])[\-]([0-9]{2})$/);    // 31-01-22 NL
        if (match) {
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


export function isRing( value ) {
    return toRing( value ) !== null; // match failed
}
// get ring parts from ring string
export function toRing( value ) {
    if( value ) {
        // try eu type ring 'D 23 AZ 999' or 'NL 23 H 1985'
        let match = value.match(/^([a-zA-Z]+)[\ \.]*(\d{2})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // D 21 AZ 999
        if (match) {
            return {country: match[1], year: match[2], code: match[3], number: match[4]}
        } else { // try defaul D ring
            match = value.match(/^(\d\d?)[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // 21 AZ 999
            if (match) {
                return {country: 'D', year: match[1], code: match[2], number: match[3]}
            }
        }
    }
    return null;
}


export function getProduction( days, eggs, dames ) {
    let fit;
    if( days > 365 ) {
        fit = 365;
    } else if( days > 183 ) {
        fit = 340.1984 - 0.7930455*days + 0.002358891*days*days;  // https://mycurvefit.com/
    } else {
        fit = 274;
    }
    console.log( "DEDF", days, eggs, dames, fit );
    const production = eggs/dames * fit/days; //fit * eggs/days/dames;
    if( production >= 0 && production <= 366 ) {
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

//export function printPct( value, decimals = 0 ) {
//    return (value*100).toFixed( decimals )+'%';
//}

export function dat( date ) { // expecting yyyy-mm-dd
    if( date ) {
        date = new Date(date);
        return date.toLocaleDateString('de-DE', {year: 'numeric', month: '2-digit', day: '2-digit'});
    }
    return '';
}

export function dec( value, decimals ) {
    if( value == null ) { // incl undefined
        return '';
    }
    value = +value; // make sure its a number
    decimals = decimals || 0;
    return value.toFixed( decimals )
}

export function pct(a, b, dec=1 ) {
    if( a != null && b != null && b !== 0 ) {
        return (100 * a / b).toFixed( dec )+'%';
    }
    return '';
}

export function txt( text ) {
    return text ? text : '';
}

export function validator( value ) {
    let valid = true;
    const worker = {
        string: () => {
            valid &= typeof value === 'string';
            return worker;
        },
        number: () => {
            valid &= isNumber( value );
            return worker;
        },
        range: ( min, max ) => {
            valid &= value >= min && value <= max;
            return worker;
        },
        date:  () => {
            valid &= ! isNaN( Date.parse( value ) );
            return worker;
        },
        ring: () => {
            valid &= isRing( value );
            return worker;
        },
        empty: () => {
            value.length === 0;
            return worker;
        },
        nullable: () => { // should be last !
            valid = value === null ? true : valid;
            return worker;
        },
        isInvalid: () => {
            return ! valid;
        }
    }
    return worker;

}