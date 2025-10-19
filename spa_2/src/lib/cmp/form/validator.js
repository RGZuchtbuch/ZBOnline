//import { isEmail, isPassword, isURL, toString, toNumber, toDate, toRing } from './util.js';

// age : Validator.number().min(0).max(100);
// console.log( age.value ); // t/f

// validity check
export function isDate( value ) {
	let date = toDate( value );
	return date !== null;
}
export function isEmail( value ) {
	if( value ) {
		const regex = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;
		return regex.test( value );
	}
	return false; // match failed
}
export function isPassword( value ) {
	// min 8 chars, having a-z, A-Z, 0-9 and one that is not a-z,A-Z, 0-9 : should match backends check !
	if( value ) {
		const regex = /(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})/;
		return regex.test( value );
	}
	return false;
}
export function isURL( value ) {
	if( value ) {
		const regex = /[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/;
		return regex.test( value );
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
			const year = toFullYear( match[3] );
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
	return date ? date.getFullYear().toString().padStart(4, '0') + '-'+(date.getMonth()+1).toString().padStart( 2, '0' )+'-'+date.getDate().toString().padStart( 2, '0' ) : null; // to formatted ISO
}
export function toDateString( date ) {
	return date ? date.getDate().toString().padStart( 2, '0' )+'.'+(date.getMonth()+1).toString().padStart( 2, '0' )+'.'+date.getFullYear().toString().padStart(4, '0') : null; // to formatted locale
}
export function toFullYear( shortYear ) { // for use with date conversion and rings
	const thisYear = new Date().getFullYear();
	const currentCentury = Math.floor( thisYear / 100 );
	const tryYear = 100 * currentCentury + Number( shortYear % 100 );
	return tryYear > thisYear+1 ? tryYear - 100 : tryYear; // allows for max next year
}
export function toNumber( value ) {
	return value !== undefined && value !== null && ! isNaN(value) ? Number( value ) : null;
}
export function toRing( value ) { // returns object for ring
	if( value ) {
		// try eu type ring  default D '23 AZ 999' or with country 'D 23 AZ 999' or 'NL 23 H 1985' with long or short year

		let ring = null;
		let match = value.match(/^(\d{2})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // 21 AZ 999
		if (match) {
			ring = {country: 'D', year: toFullYear(match[1]), code: match[2].toUpperCase(), number: match[3]}
		} else {
			match = value.match(/^([a-zA-Z]+)[\ \.]*(\d{2})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // D 21 AZ 999
			if (match) {
				ring = { country: match[1].toUpperCase(), year: toFullYear(match[2]), code: match[3].toUpperCase(), number: match[4] }
			} else {
				match = value.match(/^(\d{4})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // 2021 AZ 999
				if (match) {
					ring = { country: 'D', year: match[1], code: match[2].toUpperCase(), number: match[3] }
				}
				match = value.match(/^([a-zA-Z]+)[\ \.]*(\d{4})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // D 2021 AZ 999
				if (match) {
					ring = { country: match[1].toUpperCase(), year: match[2], code: match[3].toUpperCase(), number: match[4] }
				}
			}
		}
		if( ring ) {
			ring.name = `${ring.country} ${ring.year % 100} ${ring.code} ${ring.number}`;
			return ring;
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




export default function validator( value ) {

	let valid = true;
	const worker = { // to stream
		boolean: () => {

		},
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
		email : () => {
			valid &&= isEmail( value );
			return worker;
		},
		password : () => {
			valid &&= isPassword( value );
			return worker;
		},
		url : () => {
			valid &&= isURL( value );
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



		if : ( condition ) => {
			valid = condition ? valid : false;
			return worker;
		},
		notNull : () => {
			valid = value != null;
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





