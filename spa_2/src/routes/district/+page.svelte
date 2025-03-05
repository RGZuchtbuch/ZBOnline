<script>
	import { getContext } from 'svelte';
	import District from '$lib/cmp/district/District.svelte';

	let { data } = $props();
	let state = getContext( 'state' )

	state.title = 'Die BDRG Verbände im Zuchtbuch';
	state.menu = {
		trail : [
			{ name:'Start',      href:'/' },
			{ name:'Verbände',   href:'/district' },
		],
		options : [],
	};


</script>

<District {...data} />


