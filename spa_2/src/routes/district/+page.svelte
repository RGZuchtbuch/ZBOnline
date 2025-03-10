<script>
	//import { getContext } from 'svelte';
	import store, { federation } from '$lib/js/store.svelte.js';

	import Districts from '$lib/cmp/district/Districts.svelte';

	//let { data } = $props();
	//let state = getContext( 'state' )

	const title = 'Die BDRG Verbände im Zuchtbuch'
	const menu = {
		trail : [
			{ name:'Start',      href:'/' },
			{ name:'Verbände',   href:'/district' },
		],
		options : [],
	}

	store.title.update( () => title );
	store.menu.update( () => menu );


</script>

<Districts />


