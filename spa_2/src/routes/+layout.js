import api from '$lib/js/api.js';

export const ssr = false; // need this once for spa only in sveltekit


export async function load( { params, url } ) {
	const standard_promise = getStandardPromise();
	const fed_promise = getFederationPromise();
	const responses = await Promise.all([ fed_promise, standard_promise ])

	return { federation:responses[0], standard:responses[1], url:new URL( url ) }; // no return as all in stored state
}

// helpers


async function getStandardPromise() {
	const response = await api.standard.get();
	if( response ) {
		const standard = structuredStandardTree(response.standard); // { root, sections, breed, colors }
		standard.rootSections = getRootSections( standard )
		return standard;
	}
	return null;
}

function getRootSections( standard ) {
	const sections = [
		{ id:3,  name:'Groß u. Wassergeflügel', breeds:[] },
		{ id:11, name:'Hühner Groß', breeds:[] },
		{ id:12, name:'Zwerghühner', breeds:[] },
		{ id:13, name:'LegeWachteln', breeds:[] },
		{ id:5,  name:'Tauben', breeds:[] },
		{ id:6,  name:'Ziergeflügel', breeds:[] },
	];
	for( let section of sections ) { // each rootSection, add breed and child breeds
		addBreeds(  standard.sections[ section.id ], section.breeds ); // add to rootsection breeds, recursive
		section.breeds.sort((a, b) => a.name.localeCompare(b.name)); // sort by name
	}
	return sections;
}

// structuring
function structuredStandardTree( standard ) {
	standard.sections = {};
	standard.breeds = {};
	standard.colors = {};
	addSection( standard.root, standard );
	return standard;
}

// recursives

function addSection( section, standard ) { // recursive for sections
	standard.sections[ section.id ] = section;
	for( const child of section.children ) {
		addSection( child, standard );
	}
	for( const breed of section.breeds ) {
		standard.breeds[ breed.id ] = breed;
		for( const color of breed.colors ) {
			standard.colors[ color.id ] = color;
		}
	}
}

function addBreeds( section, breeds ) { // for rootSections
	for( const breed of section.breeds ) { // add these
		breeds.push( breed );
	}
	for( const child of section.children ) { // add children's
		addBreeds( child, breeds );
	}
}



async function getFederationPromise() {
	const response = await api.district.get( { rootId:1 } );
	if( response ) {
		let federation = {
			root : response.district,
			districts: [],
		};
		addDistrict( response.district, federation.districts );
		return federation;
	}
	return null;
}

function addDistrict( district, districts ) { // recursive for sections
	districts[ district.id ] = district;
	for( const child of district.children ) {
		addDistrict( child, districts );
	}
}