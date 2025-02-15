
import api from '$lib/js/api.js';
//import { app } from '$lib/js/store.svelte.js';

export async function load( { params } ) {
		const response = await api.result.get( { districtId:params.districtId } );
		console.log( 'results', response.results );
		const results = structure( response.results );
		console.log( 'post results', results );
		return { results:results };
};


function structure( resultList ) {
	const results = { years:[] };
	let year  = { year:0 };
	let section = { id:0 };
	let breed = { id:0 };
	for( let result of resultList ) {
		if( result.year !== year.year ) {
			year = { year:result.year, sections:[] }
			results.years.push( year );
		}
		if( result.rootsection.id !== section.id ) {
			section = { id:result.rootsection.id, name:result.rootsection.name, breeds:[] };
			year.sections.push( section );
		}
		if( result.breed.id !== breed.id ) {
			breed = { id:result.breed.id, name:result.breed.name, colors:[], result:result.color.name ? null : result }; // only result if no color
			section.breeds.push( breed );
		}
		console.log( 'color', result.id, breed.id, result.color );
		if( result.color.name ) {
			breed.colors.push( { id:result.color.id, name:result.color.name, result:result } );
		}
	}
	return results;
}