<script>
	import {getContext} from 'svelte';
	import Breeders from '$lib/cmp/moderator/district/Breeders.svelte';

	let { data } = $props();
	let state = getContext( 'state' );


	state.title = `Züchter im ${data.district.short}`;
	state.menu = {
		trail: [
			{name: 'Home', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: data.district.short, href: '/moderator/' + data.district.id},
			{name: 'Züchter', href: data.url.path },
		],
		options: [],
	}
</script>

{#if data.breeders}
	<Breeders {data} />
{/if}


