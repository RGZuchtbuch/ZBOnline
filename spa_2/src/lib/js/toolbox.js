export function dec( value, dec = 0 ) {
	value = Number( value );
	if( value ) {
		return value.toFixed( dec );
	}
	return '';
}
export function txt( text ) {
	if( text === null ) {
		return '';
	}
	return text;
}

export function daysBetween( start, end ) {
	start = new Date( start );
	end = new Date( end );
	if( end >= start ) {
		return 1 + (end - start) / (1000 * 60 * 60 * 24);
	} else {
		return null;
	}
}


