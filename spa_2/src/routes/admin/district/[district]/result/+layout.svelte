<script>

	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import { page } from '$app/state';

	let { children } = $props();

	$effect( () => {
		const query = page.url.searchParams;
		// if( query.has( 'year') ) {
		// 	ctx.year = +query.get('year');
		// } else {
		// 	ctx.year = CURRENT_INPUT_YEAR;
		// }
		ctx.year = query.has( 'year' ) ? +query.get( 'year' ) : CURRENT_INPUT_YEAR;
		ctx.district = ctx.federation.districts[ page.params.district ];
	});

</script>

{#if ctx.district && ctx.year}
	{@render children()}
{/if}

