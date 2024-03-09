import { isEmail, toString, toNumber, toDate, toRing } from './util.js';

// age : Validator.number().min(0).max(100);
// console.log( age.value ); // t/f

import {isNullOrUndef} from "chart.js/helpers";

export default function validator(value ) {

    let valid = true;
    const worker = { // to stream
        string : () =>{
            const stringValue = toString( value );
            if( stringValue !== null ) { // note could be ''
                value = stringValue;
            } else {
                valid = false;
            }
            return worker;
        },
        number : () => {
            const numberValue = toNumber( value );
            if( numberValue !== null ) { // note, could be 0
                value = numberValue;
            } else {
                valid = false;
            }
            return worker;
        },
        date : () => {
            const dateValue = toDate( value );
            if( dateValue !== null ) {
                value = dateValue;
            } else {
                valid = false;
            }
            return worker;
        },
        ring : () => {
            const ringValue = toRing( value );
            if( ringValue !== null ) {
                value = ringValue
            } else {
                valid = false;
            }
            return worker;
        },


        true : () => {
            valid &&= value;
            return worker;
        },

        range : ( min, max ) => { // numbers
            valid &&= min <= value && value <= max;
            return worker;
        },

        after : ( date ) => { // iso date
            const other = toDate( date );
            if( value && other ) {
                valid &&= value.getTime() > other.getTime();
            } else {
                valid = false;
            }
            return worker;
        },
        before : ( date ) => { // iso date
            const other = toDate( date );
            if( value && other ) {
                valid &&= value.getTime() < other.getTime();
            } else {
                valid = false;
            }
            return worker;
        },
        between : ( after, before ) => { // iso dates
            const afterDate = toDate( after )
            const beforeDate = toDate( before )
            if( value && afterDate && beforeDate ) {
                valid &&= value.getTime() >=  afterDate.getTime() && value.getTime() <= beforeDate.getTime();
            } else {
                valid = false;
            }
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

        if : ( condition ) => {
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





