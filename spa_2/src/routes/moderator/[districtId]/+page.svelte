<script>
	import { page } from '$app/state';
	import { app } from '$lib/js/store.svelte.js';
	import District from '$lib/cmp/moderator/District.svelte';

//    let { data } = $props();

	const path = page.url.pathname;

	$effect( () => {

		app.title = app.district ? `Verbande ${app.district.name} verwalten` : '';
		app.menu.trail = [
			{ name:'Home',      href:'/' },
			{ name:'Obmann',    href:'/moderator' },
			{ name:app.district?app.district.short:'?',   href:path },
		];
		app.menu.options = [
			{ name:'Leistungen', href:path+'/result' },
			{ name:'Stämme',     href:path+`/pair` },
			{ name:'Züchter',    href:path+`/breeder` },
			{ name:'Berichte',   href:path+`/report` },
		];

	})

    console.log( 'District page', path );

</script>

<District district={app.district} />

