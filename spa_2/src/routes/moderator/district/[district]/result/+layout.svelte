<script>

	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import {activeYear} from '$lib/js/tools.js';
	import {page} from '$app/state';
	import {goto} from '$app/navigation';

	let { children } = $props();

	$effect( () => {

		//ctx.year = +( page.url.searchParams.get( 'year') ?? activeYear() ); // error
		///ctx.year = ctx.year ?? activeYear();

		const query = page.url.searchParams;
		if( query.has( 'year') ) {
			console.log( 'Found ', +query.get('year'))
			ctx.year = +query.get('year');
		} else {
			console.log( 'Set year')
			const year = activeYear();
			ctx.year = year;
			const url = new URL( page.url );
			url.searchParams.set( 'year', year );
			goto( url.href );
		}
		// 	let url = new URL( page.url ); // page.url is immutable
		// 	url.searchParams.set( 'year', year );
		// 	goto( url.href );
		// }
		// ctx.year = query.has( 'year') ? +query.get( 'year' ) : activeYear();
		ctx.district = ctx.federation.districts[ page.params.district ];
		//console.log( 'effect', ctx.year ); // cyclic
		//loadDistrict(6);
	});

	// function loadDistrict( id ) {
	// 	console.log( 'Layout loads district' );
	// 	ctx.district = ctx.federation.districts[ id ];//store.federation.districts[ +page.params.district ];
	// }

</script>

{#if ctx.district && ctx.year}
	{@render children()}
{/if}

