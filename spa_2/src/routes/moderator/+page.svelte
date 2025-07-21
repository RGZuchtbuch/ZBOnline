<script>
	import { getContext, setContext } from 'svelte';	import {page} from '$app/state';
	import {goto} from '$app/navigation';
	import { ctx } from '$lib/js/store.svelte.js';
	import Districts from '$lib/cmp/moderator/Districts.svelte';

	let { data } = $props();

	let districts = $state( null );
	setContext( 'districts', districts );
	ctx.districts = null; // clear old districts
	$effect( () => {
		ctx.districts = data.districts;
		districts = data.districts;
	}); // in context to avoid warnings on wrong updates.

	$effect( () => {
		ctx.header = {
			title: 'Obmann: Verbände zum Verwalten',
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

<Districts districts={data.districts}/>



