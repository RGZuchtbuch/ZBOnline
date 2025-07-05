import model from '$lib/js/model.js';

export async function load( { depends, fetch, params } ) {
	depends( 'article' );
	console.log( 'Loading Article' );
	const response = await Promise.all( [
		model.Article.load( params.article ),
	] );
	return { article:response[0] };
}
