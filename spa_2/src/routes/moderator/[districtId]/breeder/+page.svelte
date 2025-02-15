<script>
	import { page } from '$app/state';
	import { app } from '$lib/js/store.svelte.js';
	import District from '$lib/cmp/moderator/District.svelte';
	import Breeders from '$lib/cmp/moderator/Breeders.svelte';

	let { data } = $props();

	const path = page.url.pathname;

	$effect( () => {
		if( app.district ) {
			app.title = `Stämme und Paare für ${data.district.name}`;
			app.menu.trail = [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: data.district.short, href: '/moderator/' + data.district.id},
				{name: 'Stämme', href: path},
			];
			app.menu.options = [];
		}
	} );
    console.log( 'Results page', path );

</script>

{#if data.breeders}
	<Breeders breeders={data.breeders} />
{/if}


