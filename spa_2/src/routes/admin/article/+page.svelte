<script>
	import { getContext } from 'svelte';
	import Articles from '$lib/cmp/article/Articles.svelte';

	let state     = getContext( 'state' );

	state.title = 'Obmann';
	state.menu = {
		trail : [
			{ name:'Start',    href:'/' },
			{ name:'Admin',    href:'/admin' },
			{ name:'Artikel',  href:'/admin/articles' },
		],
	    options : [
	    ],
	};



</script>

<Articles role='admin'/>



