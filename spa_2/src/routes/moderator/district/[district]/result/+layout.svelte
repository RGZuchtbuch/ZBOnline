<script>
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';

	let { children } = $props();

	$effect( () => {
		const query = page.url.searchParams;
		// if( query.has( 'year') ) {
		// 	ctx.year = +query.get('year');
		// } else {
		// 	const year = activeYear();
		// 	ctx.year = year;
		// 	// const url = new URL( page.url );
		// 	// url.searchParams.set( 'year', year );
		// 	//goto( url.href );
		// }
		console.log( 'Year type string ?', typeof ctx.year === 'string' ? 'true' : 'false');

		ctx.year = query.has( 'year' ) ? +query.get( 'year' ) : ctx.year ? +ctx.year : +CURRENT_INPUT_YEAR;
		ctx.district = ctx.federation.districts[ page.params.district ];
	});

</script>

{#if ctx.district && ctx.year}
	{@render children()}
{/if}

