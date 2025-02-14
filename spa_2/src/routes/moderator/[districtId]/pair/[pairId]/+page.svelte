<script>
	import { page } from '$app/state';
	import { app } from '$lib/js/store.svelte.js';
	import District from '$lib/cmp/moderator/District.svelte';

    let { data } = $props();

	const path = page.url.pathname;

    app.title = `Stämme und Paare für ${data.district.name}`;
	app.menu.trail = [
        { name:'Home',                   href:'/' },
        { name:'Obmann',                href:'/moderator' },
        { name:data.district.short,     href:'/moderator/'+data.pair.id },
		{ name:'Stämme',                href:'/moderator/'+data.pair.id+'/pair' },
		{ name:data.pair.id,            href:path },
    ];
	app.menu.options = [];

    console.log( 'Results page', path );

</script>

District Pair




