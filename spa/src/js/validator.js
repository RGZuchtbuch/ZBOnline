

// age : Validator.number().min(0).max(100);
// console.log( age.value ); // t/f

export default function validator(value ) {

    let valid = true;
    const worker = { // to stream
        number : () => {
            valid &&= isNumber( value );
            return worker;
        },
        range : ( min, max ) => {
            valid &&= min <= value && value <= max;
            return worker;
        },

        date : () => {
            valid &&= isDate( value );
            return worker;
        },
        after : ( date ) => {
            const one = toDate( value );
            const other = toDate( date );
            if( one !== null && other !== null ) {
                valid &&= one.getTime() > other.getTime();
            } else {
                valid = false;
            }
            return worker;
        },
        before : ( date ) => {
            const one = toDate( value );
            const other = toDate( date );
            if( one && other ) {
                valid &&= one.getTime() < other.getTime();
            } else {
                valid = false;
            }
            return worker;
        },

        string : () =>{
            valid &&= isString( value );
            return worker;
        },
        length : ( min, max ) => {
            valid &&= min <= value.length && value.length <= max;
            return worker;
        },

        email : () => {
            valid &&= isEmail( value );
            return worker;
        },
        ring : () => {
            valid &&= isRing( value );
            return worker;
        },
        if : (condition ) => {
            valid &&= condition;
            return worker;
        },
        orNull : () => { // last check
            valid = value === null ? true : valid; // true is null, otherwise unchanged
            return worker;
        },
        orNullIf : ( condition ) => { // last check
            valid = condition && value === null ? true : valid;
            return worker;
        },
        isValid : () => { // last
            return valid;
        },
        isInvalid : () => {
            return ! valid;
        },

    }
    return worker;
}

export function isNumber( value ) {
    return typeof value === "number" && !Number.isNaN(value);
}
export function isString( value ) {
    return typeof value === 'string' || value instanceof String;
}
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
export function isRing( value ) {
    return toRing( value ) !== null; // match failed
}

export function toDate( input, yearsAhead = 10 ) {
    if( input ) {

        const currentYear = new Date().getFullYear();
        const currentCentury = Math.floor( currentYear / 100 );

        let match =
            input.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\.\-](1[0-2]|0[1-9]|[1-9])[\.\-]([0-9]{2})$/);  // 31.01.22 D or 31-01-22
        if (match) {
            let tempYear = 100 * currentCentury + Number(match[3]);
            let year = tempYear - currentYear > yearsAhead ? tempYear - 100 : tempYear;
            return new Date(year, match[2] - 1, match[1]);
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
export function toRing( value ) {
    if( value ) {
        // try eu type ring 'D 23 AZ 999' or 'NL 23 H 1985'
        let match = value.match(/^([a-zA-Z]+)[\ \.]*(\d{2})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // D 21 AZ 999
        if (match) {
            return {country: match[1].toUpperCase(), year: match[2], code: match[3].toUpperCase(), number: match[4]}
        } else { // try defaul D ring
            match = value.match(/^(\d\d?)[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // 21 AZ 999
            if (match) {
                return {country: 'D', year: match[1], code: match[2].toUpperCase(), number: match[3]}
            }
        }
    }
    return null;
}


