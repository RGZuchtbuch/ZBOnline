<script>

	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import {activeYear} from '$lib/js/tools.js';
	import {page} from '$app/state';
	import {goto} from '$app/navigation';

	let { children } = $props();

	$effect( () => {
		const query = page.url.searchParams;
		if( query.has( 'year') ) {
			ctx.year = +query.get('year');
		} else {
			const year = activeYear();
			ctx.year = year;
			// const url = new URL( page.url );
			// url.searchParams.set( 'year', year );
			//goto( url.href );
		}
		ctx.district = ctx.federation.districts[ page.params.district ];
	});

</script>

{#if ctx.district && ctx.year}
	{@render children()}
{/if}

