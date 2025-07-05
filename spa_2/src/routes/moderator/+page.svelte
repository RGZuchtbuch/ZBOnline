<script>
	import {page} from '$app/state';
	import {goto} from '$app/navigation';
	import { ctx } from '$lib/js/store.svelte.js';
	import Districts from '$lib/cmp/moderator/Districts.svelte';

	let { data } = $props();


	$effect( () => {
		ctx.districts = data.districts;
	}); // in context to avoid warnings on wrong updates.

	$effect( () => {
		ctx.header = {
			title: 'Obmann',
			menu: {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Obmann'},
				],
				options: [],
			},
		}
	});

</script>

<Districts districts={ctx.districts}/>



