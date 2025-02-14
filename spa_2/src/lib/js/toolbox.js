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

export function calculateLay( start, end, dames, eggs ) {
	const days = daysBetween( start, end );
	if( days && dames >= 1 && eggs >= 0 ) {
		let yearEggs = 365 * eggs / dames / days;
		let correctedEggs = yearEggs * ( 0.75 + 0.0000456621*days - 2.502033e-7*days*days - 5.483908e-9*days*days*days + 3.004881e-11*days*days*days*days );
		return Math.round( 10 * correctedEggs ) / 10;

		// fit : y = 0.75 + 0.0000456621*x - 2.502033e-7*x^2 - 5.483908e-9*x^3 + 3.004881e-11*x^4

	} else {
		return null;
	}
}
