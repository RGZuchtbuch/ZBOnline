import model from '$lib/js/model.js';

export async function load( { params, parent } ) {
	console.log( 'Loading Breeder, nothing to do here as not logged in' );
	const { federation, standard, user } = await parent();
	//const response = await Promise.all( [
	//	model.Article.load( params.article ),
	//] );
	//return { article:response[0] };
	return {};
}
