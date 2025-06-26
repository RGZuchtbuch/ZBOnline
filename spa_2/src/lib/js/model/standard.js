import api from '$lib/js/server.js'; // get post put delete



export default class Standard {
	static async load() {
		let standard = null
		const data= await api.get(`/api/2/standard` );
		if( data && data.standard ) {
			standard = structuredStandardTree( data.standard ); // { root, sections, breed, colors }
			standard.rootSections = getRootSections( standard )
		}
		return standard;
	}
};


// async function getStandardPromise() {
// 	const response = await api.standard.get();
// 	if( response ) {
// 		const standard = structuredStandardTree(response.standard); // { root, sections, breed, colors }
// 		standard.rootSections = getRootSections( standard )
// 		return standard;
// 	}
// 	return null;
// }

// main sections to enter results for
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

// make sections, breeds and colors available by id
function structuredStandardTree( standard ) {
	standard.sections = {};
	standard.breeds = {};
	standard.colors = {};
	addSection( standard.root, standard );
	return standard;
}
// add sections, breeds and colors for sections incl subsections
function addSection( section, standard ) { // recursive for sections
	standard.sections[ section.id ] = section;
	for( const breed of section.breeds ) {
		standard.breeds[ breed.id ] = breed;
		for( const color of breed.colors ) {
			standard.colors[ color.id ] = color;
		}
	}
	for( const child of section.children ) { // nesting
		addSection( child, standard );
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
