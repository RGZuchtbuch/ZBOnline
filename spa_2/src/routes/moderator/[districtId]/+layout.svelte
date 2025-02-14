<script>
	import { page } from '$app/state';
	import api from '$lib/js/api.js';
	import { app } from '$lib/js/store.svelte.js';
	import {onMount} from 'svelte';

	let { children } = $props();

	onMount( async () => {
		const response = await api.district.get( page.params.districtId );
		app.district = response.district;
	} );

</script>

{@render children()}