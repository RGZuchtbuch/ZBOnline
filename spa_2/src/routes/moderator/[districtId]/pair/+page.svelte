<script>
	import { page } from '$app/state';
	import { store } from '$lib/js/store.svelte.js';
	import District from '$lib/cmp/moderator/District.svelte';
	import Pairs from '$lib/cmp/moderator/district/Pairs.svelte';

    let { data } = $props();

	const path = page.url.pathname;

    app.title = `Stämme und Paare für ${ data.district.name }`;
	app.menu.trail = [
        { name:'Home',      href:'/' },
        { name:'Obmann',    href:'/moderator' },
        { name:data.district.short,   href:'/moderator/'+data.district.id },
		{ name:'Stämme', href:path },
    ];
	app.menu.options = [];

    console.log( 'Results page', path );

</script>

<Pairs {...data} />



