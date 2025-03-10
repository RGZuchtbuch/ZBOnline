<script>
	//import { getContext } from 'svelte';
	import store from '$lib/js/store.svelte.js';

	import Standard from '$lib/cmp/standard/Standard.svelte';

//	let { data } = $props();
//	let state = getContext( 'state' )

	const title = 'Die anerkannten Rassen im BDRG';
	const menu = {
		trail : [
			{ name:'Start',      href:'/' },
			{ name:'Standard',   href:'/standard' },
		],
		options : [],
	};

	store.title.update( () => title );
	store.menu.update( () => menu );

</script>

<Standard />

