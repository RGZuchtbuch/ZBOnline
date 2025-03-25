<script>
	import { getContext } from 'svelte';
	import store, { } from '$lib/js/store.svelte.js';

	import Articles from '$lib/cmp/article/Articles.svelte';
	import api from '$lib/js/api.js';

	let { data } = $props();

	//let articles = $state( null );

	//let app    = getContext( 'state' );

	$effect( ()=>{
		setHeader( data );
	});





	function setHeader() {
		const title = 'Beiträge zum BDRG Zuchtbuch';
		const menu = {
			trail : [
				{ name:'Start',  href:'/' },
				{ name:'Info',   href:'/article' },
			],
			options : [],
		};
		store.title.update( () => title );
		store.menu.update( () => menu );
	}

</script>

<Articles articles={data.articles} />
