<script>
	import { page } from '$app/state';
	import { store } from '$lib/js/store.svelte.js';
	import District from '$lib/cmp/moderator/District.svelte';
	import Pair from '$lib/cmp/pair/Pair.svelte';

    let { data } = $props();

	// make reactive
	let standard = $state( data.standard );
	let pair     = $state( data.pair );

	const path = page.url.pathname;

	console.log( 'D', data );

    app.title = `Stämme und Paare für ${data.district.name}`;
	app.menu.trail = [
        { name:'Home',                   href:'/' },
        { name:'Obmann',                href:'/moderator' },
        { name:data.district.short,     href:'/moderator/'+data.district.id },
		{ name:'Stämme',                href:'/moderator/'+data.district.id+'/pair' },
		{ name:data.pair.id,            href:'/moderator/'+data.district.id+'/pair'+data.pair.id },
    ];
	app.menu.options = [];

</script>

<Pair bind:pair {standard} />




