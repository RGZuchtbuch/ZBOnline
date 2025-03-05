<script>
	import { getContext } from 'svelte';
	import Standard from '$lib/cmp/standard/Standard.svelte';

	let { data } = $props();
	let state = getContext( 'state' )

	state.title = 'Die anerkannten Rassen im BDRG';
	state.menu = {
		trail : [
			{ name:'Start',      href:'/' },
			{ name:'Standard',   href:'/standard' },
		],
		options : [],
	};

</script>

<Standard {...data} />

