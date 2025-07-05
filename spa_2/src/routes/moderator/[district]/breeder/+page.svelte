<script>

	import {page} from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';

	import Breeders from '$lib/cmp/moderator/district/Breeders.svelte';

	let { data } = $props();

	$effect( async () => {
		ctx.header.title = `Züchter im ${data.district.name}`;
		ctx.header.menu = {
			trail: [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: data.district.short, href: `/moderator/${data.district.id}` },
				{name: 'Züchter' },
			],
			options: [
				{ name:'Eingaben', href:`/moderator/${data.district.id}/result` },
			],
		}
	})

</script>

{#if data.breeders}
	<Breeders breeders={data.breeders} district={data.district} />
{/if}


