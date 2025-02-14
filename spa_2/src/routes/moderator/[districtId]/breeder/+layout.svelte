<script>
	import { page } from '$app/state';
	import api from '$lib/js/api.js';
	import {app} from '$lib/js/store.svelte.js';
	import {onMount} from 'svelte';

	let { children } = $props();

	onMount( async () => {
		app.breeders = [];
		const response = await api.breeder.get( { districtId:page.params.districtId } );
		if( response ) {
			app.breeders = response.breeders;
		}
	} );


</script>

{@render children()}
