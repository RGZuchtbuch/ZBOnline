<script>
	import { page } from '$app/state';
	import api from '$lib/js/api.js';
	import {app} from '$lib/js/store.svelte.js';
	import {onMount} from 'svelte';

	let { children } = $props();

	onMount( async () => {
		app.breeder = null;
		app.pairs = [];
		const breederId = page.params.breederId;
		const responses = await Promise.all( [ api.breeder.get( breederId ), api.pair.get( { breederId:breederId } ) ] );
		if( responses ) {
			app.breeder = responses[0].breeder;
			app.pairs = responses[1].pairs;
			console.log( app.breeder, app.pairs );
		}
	});


</script>

{@render children()}
