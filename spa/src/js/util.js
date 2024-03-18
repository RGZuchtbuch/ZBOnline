/**
 * Collection of helper functions
 */

/****** MAP ******/

// for use with map
function mercY( lat ) {
    return Math.log( Math.tan( Math.PI/4 + lat/2 ) );
}
// for use with map, mercator coords ( google maps ) to pixel
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
// calc color for value in range ( map )
export function calcColor( min, max, value, alpha = 1, blue = 0 ) {
    const relValue = (value-min)/(max-min);
    //const mid = (min+max)/2;
    let r = 15;
    let g = 15;
    let b = Math.round( Math.min( 15*blue,  15 ) ); // default 0
    let a = Math.round( Math.min( 15*alpha, 15 ) ); // default 1
    if( relValue < 0.5 ) { // get color on scale 0.15
        g = Math.round( 15 * 2 * relValue );
    } else {
        r = Math.round( 15 * 2 * ( 1 - relValue ) );
    }
    //alpha = Math.round( 15 * alpha ); // range 0..1 to 0..15
    return '#'+r.toString(16)+g.toString(16)+b.toString(16)+a.toString(16); // only 1 char per color, like '#48f7' making '#4488ff77'
}



/******* for forms and more *******/

// validity check
export function isDate( value ) {
    let date = toDate( value );
    return date !== null;
}
export function isEmail( value ) {
    if( value ) {
        return value.match( '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$' ) !== null; // a.a@a.aa
    }
    return false; // match failed
}
export function isNumber( value ) {
    return value !== undefined && value !== null && ! isNaN(value);
}
export function isString( value ) {
    return value !== undefined && value !== null;
}
export function isRing( value ) {
    return toRing( value ) !== null; // match failed
}

// convert to type if can or null
export function toDate( input, yearsAhead = 10 ) {
    if( input ) {
        let match = input.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\.\-](1[0-2]|0[1-9]|[1-9])[\.\-]([0-9]{2})$/);  // 31.01.22 D or 31-01-22
        if (match) {
            const year = shortToFullYear( match[3] );
            return new Date( year, match[2] - 1, match[1]);
        } else {
            match =
                input.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\.\-](1[0-2]|0[1-9]|[1-9])[\.\-]([0-9]{4})$/);     // 31.01.2022 D or 31-01-2022 NL
            if (match) {
                return new Date(match[3], match[2]-1, match[1]);
            } else {
                match =
                    input.match(/^([0-9]{4})[\-\.](1[0-2]|0[1-9]|[1-9])[\-\.](3[0-1]|[12][0-9]|0[1-9]|[1-9])$/);    // 2022-7-22 ISO
                if (match) {
                    return new Date(match[1], match[2]-1, match[3]);
                }
            }
        }
    }
    return null;
}
export function toDateISO( date ) {
    return date ? date.getFullYear().toString().padStart(4, '0') + '-'+(date.getMonth()+1).toString().padStart( 2, '0' )+'.'+date.getDate().toString().padStart( 2, '0' ) : null; // to formatted ISO
}
export function toDateString( date ) {
    return date ? date.getDate().toString().padStart( 2, '0' )+'.'+(date.getMonth()+1).toString().padStart( 2, '0' )+'.'+date.getFullYear().toString().padStart(4, '0') : null; // to formatted locale
}

export function toNumber( value ) {
    return value !== undefined && value !== null && ! isNaN(value) ? Number( value ) : null;
}
export function toRing( value ) { // returns object for ring
    if( value ) {
        // try eu type ring  default D '23 AZ 999' or with country 'D 23 AZ 999' or 'NL 23 H 1985' with long or short year

        let match = value.match(/^(\d{2})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // 21 AZ 999
        if (match) {
            return {country: 'D', year: shortToFullYear(match[1]), code: match[2].toUpperCase(), number: match[3]}
        } else {
            match = value.match(/^([a-zA-Z]+)[\ \.]*(\d{2})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // D 21 AZ 999
            if (match) {
                return { country: match[1].toUpperCase(), year: shortToFullYear(match[2]), code: match[3].toUpperCase(), number: match[4] }
            } else {
                match = value.match(/^(\d{4})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // 2021 AZ 999
                if (match) {
                    return { country: 'D', year: match[1], code: match[2].toUpperCase(), number: match[3] }
                }
                match = value.match(/^([a-zA-Z]+)[\ \.]*(\d{4})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // D 2021 AZ 999
                if (match) {
                    return { country: match[1].toUpperCase(), year: match[2], code: match[3].toUpperCase(), number: match[4] }
                }
            }
        }
    }
    return null;
}

export function toRingString( ring ) { // ring object to string
    return ring.country+' '+ (ring.year%100) +' '+ring.code+' '+ring.number;
}

export function toString( value ) {
    return value !== undefined && value !== null ? String( value ) : null;
}

export function shortToFullYear( shortYear ) { // for use with date conversion and rings
    const currentCentury = Math.floor( new Date().getFullYear() / 100 );
    const tryYear = 100 * currentCentury + Number( shortYear );
    return tryYear > MAXYEAR ? tryYear - 100 : tryYear;
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

// eggs count to egg production, experimental
export function getProduction( days, eggs, dames ) {
    let fit;
    if( days > 365 ) {
        fit = 365;
    } else if( days > 183 ) {
        fit = 340.1984 - 0.7930455*days + 0.002358891*days*days;  // https://mycurvefit.com/
    } else {
        fit = 274;
    }
    const production = eggs/dames * fit/days; //fit * eggs/days/dames;
    if( production >= 0 && production <= 366 ) {
        return production;
    }
    return null;
}


/****** print helpers ******/
export function dat( date ) { // expecting yyyy-mm-dd from db
    if( date ) {
        date = new Date(date);
        return date.toLocaleDateString('de-DE', {year: 'numeric', month: '2-digit', day: '2-digit'});
    }
    return '';
}
export function dec( value, decimals = 0 ) {
    if( value == null ) { // incl undefined
        return '';
    }
    value = +value; // make sure its a number
    return value.toFixed( decimals )
}
export function pct(a, b, decimals=1 ) {
    if( a != null && b != null && b !== 0 ) {
        return (100 * a / b).toFixed( decimals )+'﹪';
    }
    return '';
}
export function txt( text ) {
    return text ? text : '';
}

/* moved to validator.js */
//TODO remove
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
            valid &= value === null || value.length === 0;
            return worker;
        },
        nullable: () => { // should be last !
            valid = value === null ? true : valid;
            return worker;
        },
        isNull( other ) {
            valid &= other === null;
            return worker;
        },
        notNull( other ) {
            valid &= other !== null;
            return worker;
        },

        isValid: () => {
            return valid;
        },
        isInvalid: () => {
            return ! valid;
        }
    }
    return worker;

}

