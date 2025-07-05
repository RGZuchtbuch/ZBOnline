<script>
	//import { invalidate } from '$app/navigation';
	import {page} from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';
	import Results from '$lib/cmp/moderator/district/Results.svelte';

	let { data } = $props();

	$effect( () => {
		ctx.district = data.district;
		ctx.year = data.year;
		ctx.results = data.results;
	}); // in context to avoid warnings on wrong updates.

	//let district   = $derived( data.federation.districts[ +page.params.district ] );
	//let year       = $derived( +page.url.searchParams.get( 'year' ) || new Date().getFullYear()-1 );
	//let results    = $state( null );

	$effect( async () => {
		ctx.header = {
			title: `Eingaben für ${ctx.district.name} ${ctx.year}`,
			menu: {
				trail: [
					{name: 'Home', href: '/'},
					{name: 'Obmann', href: '/moderator'},
					{name: ctx.district.short, href: `/moderator/${ctx.district.id}`},
					{name: 'Eingaben'},
				],
				options: [
					{name: 'Eingeben', href: `/moderator/${ctx.district.id}/result/edit`},
					{name: 'Züchter', href: `/moderator/${ctx.district.id}/breeder`},
				],
			}
		}
	});

	// async function load( url ) {
	// 	console.log(' Load result', year );
	// 	const response = await api.result.get( { districtId:district.id, year:year } );
	// 	results = response.results;
	// }

	console.log( 'Ctx', ctx.district );

</script>

{#if ctx.district && ctx.year && ctx.results}
	<Results district={ctx.district} year={ctx.year} results={ctx.results} />
{:else}
	Warten
{/if}
