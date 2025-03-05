<script>
	import { getContext } from 'svelte';

	import Articles from '$lib/cmp/article/Articles.svelte';

	let { data } = $props();

	let state    = getContext( 'state' );

	state.title = 'Info zum BDRG Zuchtbuch';
	state.menu = {
		trail : [
			{ name:'Start',  href:'/' },
			{ name:'Info',   href:'/article' },
		],
		options : [],
	};

</script>

<Articles {...data} />
