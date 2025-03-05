<script>
	import {setContext} from 'svelte';
	import store from '$lib/js/store.svelte.js';

	let { children, data } = $props(); // get page
	//
	// let results = $state( data.results );
	// let year = $state( data.year );
	//
	// setContext( 'results', () => results );
	// setContext( 'year', () => year );
	//
	// store.results = data.results;
	// store.year = data.year;


</script>

{@render children()}
