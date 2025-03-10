<script>
	import { getContext } from 'svelte';
	import store, { articles } from '$lib/js/store.svelte.js';

	import Articles from '$lib/cmp/article/Articles.svelte';

	//let { data } = $props();

	let app    = getContext( 'state' );

	const title = 'Info zum BDRG Zuchtbuch';
	const menu = {
		trail : [
			{ name:'Start',  href:'/' },
			{ name:'Info',   href:'/article' },
		],
		options : [],
	};

	store.title.update( () => title );
	store.menu.update( () => menu );

</script>

<Articles />
