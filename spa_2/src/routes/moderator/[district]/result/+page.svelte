<script>
	//import { invalidate } from '$app/navigation';
	import {page} from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';
	import Results from '$lib/cmp/moderator/district/Results.svelte';

	let { data } = $props();

	ctx.year = null;
	ctx.results = null;
	$effect( () => {
		ctx.year = data.year;
		ctx.results = data.results;
	}); // in context to avoid warnings on wrong updates.

	$effect( async () => {
		ctx.header = {
			title: `Eingaben für ${data.district.name} ${data.year}`,
			menu: {
				trail: [
					{name: 'Home', href: '/'},
					{name: 'Obmann', href: '/moderator'},
					{name: data.district.short, href: `/moderator/${data.district.id}`},
					{name: 'Eingaben'},
				],
				options: [
					{name: 'Eingeben', href: `/moderator/${data.district.id}/result/edit`},
					{name: 'Züchter', href: `/moderator/${data.district.id}/breeder`},
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

{#if data.district && data.year && data.results}
	<Results district={data.district} year={data.year} results={data.results} />
{:else}
	Warten
{/if}
