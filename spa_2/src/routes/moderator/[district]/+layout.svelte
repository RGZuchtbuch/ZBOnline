<script>

	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import {activeYear} from '$lib/js/tools.js';
	import {page} from '$app/state';

	let { children } = $props();

	$effect( () => {

		//ctx.year = +( page.url.searchParams.get( 'year') ?? activeYear() ); // error
		///ctx.year = ctx.year ?? activeYear();

		const query = page.url.searchParams;
		ctx.year = query.has( 'year') ? +query.get( 'year' ) : activeYear();
		ctx.district = ctx.federation.districts[ page.params.district ];
		//console.log( 'effect', ctx.year ); // cyclic
		//loadDistrict(6);
	});

	// function loadDistrict( id ) {
	// 	console.log( 'Layout loads district' );
	// 	ctx.district = ctx.federation.districts[ id ];//store.federation.districts[ +page.params.district ];
	// }

</script>

{#if ctx.district}
	{@render children()}
{/if}

