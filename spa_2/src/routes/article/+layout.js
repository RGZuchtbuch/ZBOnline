import model from '$lib/js/model.js';

export async function load( { depends, fetch, params } ) {
	depends( 'article' );
	console.log( 'Loading Articles' );
	const response = await Promise.all( [
		model.Article.query()
	] );

	return { articles:response[0] };
}
