import { ctx } from '$lib/js/store.svelte.js';

export function dec( value, dec = 0 ) {
	value = Number( value );
	if( value ) {
		return value.toFixed( dec );
	}
	return '';
}
export function pct(a, b, decimals= 0 ) {
	if( a != null && b != null && b !== 0 ) {
		return (100 * a / b).toFixed( decimals )+'﹪';
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
	if( start && end ) {
		start = new Date(start);
		end = new Date(end);
		if( end >= start ) {
			return 1 + (end - start) / (1000 * 60 * 60 * 24);
		}
	}
	return null;
}

export function getLayResult( days, eggs, dames ) {
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


export let ArgsBuilder = {

	init : () => {
		return {};
	},

	setNumber : (args, searchParams, key, init) => {
		if ( searchParams.has(key) ) {
			args[key] = +searchParams.get(key);
		} else if (init) {
			args[key] = +init;
		}
	},

	setString : (args, searchParams, key, init) => {
		if ( searchParams.has(key) ) {
			args[key] = searchParams.get(key);
		} else if (init) {
			args[key] = init;
		}
	},
}

// ****************** Name printing ****************//

export function fullName( person ) { // Eelco von Jannink
	if( person ) {
		return `${txt(person.firstname)} ${txt(person.infix)} ${txt(person.lastname)}`;
	}
	return '-';
}

export function selectName( person ) { // Jannink, Eelco von
	if( person ) {
		return `${txt(person.lastname)}, ${txt(person.firstname)} ${txt(person.infix)} `;
	}
	return '-';
}

export function shortName( person ) { // E.J
	if( person ) {
		return `${person.firstname.at(0)}.${person.lastname.at(0)} `;
	}
	return '-';
}

// ****************** Date helpers *******************//

export function completedYear( person ) { // for guests reading
	const now = new Date();
	const year = now.getFullYear();
	return now.getMonth() < 2 ? year-2 : year-1;
}

export function activeYear() { // default year, after oct 1st is this year, for moderators work
	const now = new Date();
	const year = now.getFullYear();
	return now.getMonth() < 2 ? year-1 : year;
}


export function addCrumb( crumb ) { // { name:, url: }
	// console.log( 'Add crumb', crumb.url.href );
	// let index =ctx.crumbs.findIndex( c => c.url.pathname === crumb.url.pathname );
	// if( index>= 0 ) {
	// 	ctx.crumbs[ index ].url = crumb.url;
	// 	ctx.crumbs.length = index+1; // remove trailing
	// } else {
	// 	ctx.crumbs.push( crumb );
	// }
}