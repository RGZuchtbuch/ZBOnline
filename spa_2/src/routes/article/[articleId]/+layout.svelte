<script>
	import {onMount} from 'svelte';
	import { page } from '$app/state';
	import store, {article} from '$lib/js/store.svelte.js';
	import { load } from './load.js';
	let { children, data } = $props();


	load( page );
	//article.update( () => data.article );

	onMount( async () => {
		// loads article in store
	})

</script>


{@render children()}