<script>

	import {page} from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';

	import Breeders from '$lib/cmp/moderator/district/Breeders.svelte';

	let { data } = $props();

	ctx.breeders = data.breeders;
	ctx.district = data.district;
	// $effect( () => {
	// 	ctx.breeders = data.breeders;
	// })

	$effect( async () => {
		ctx.header.title = `Züchter im ${ctx.district.name}`;
		ctx.header.menu = {
			trail: [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: data.district.short, href: `/moderator/${ctx.district.id}` },
				{name: 'Züchter' },
			],
			options: [
				{ name:'Eingaben', href:`/moderator/${ctx.district.id}/result` },
			],
		}
	})

</script>

{#if data.breeders}
	<Breeders breeders={ctx.breeders} district={ctx.district} />
{/if}


