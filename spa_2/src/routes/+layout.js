export const ssr = false;

import api from '$lib/js/api.js';
import { app } from '$lib/js/store.svelte.js';

export async function load ({ fetch, params }) {
	const standard_promise = getStandard();
	const districts_promise = getDistricts();

	const responses = await Promise.all( [ districts_promise, standard_promise ] )

	//app.districts = responses[0]; // sideeffect, moved to layout.svelte
	//app.standard = responses[1];
	return { districts:responses[0], standard:responses[1] };
}

// get promise

async function getStandard() {
	const response = await api.standard.get();
	if( response ) {
		return structuredStandardTree(response.standard); // { root, sections, breed, colors }
	}
	return null;
}


async function getDistricts() {
	const response = await api.district.get( { rootId:1 } );
	if( response ) {
		return structuredDistrictTree( response.district ); // { root, districts by id }
	}
	return null;
}

// structuring

function structuredDistrictTree( root ) {
	const districts = {};
	districts.root = root;
	addDistrict( root, districts );
	return districts;
}

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

function addDistrict( district, districts ) { // recursive for sections
	districts[ district.id ] = district;
	for( const child of district.children ) {
		addDistrict( child, districts );
	}
}


