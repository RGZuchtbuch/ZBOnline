<script>
	import {getContext} from 'svelte';
	import store from '$lib/js/store.svelte.js';
	import Results from '$lib/cmp/moderator/district/Results.svelte';

	let { data } = $props();

	let state     = getContext( 'state' );
	let results  = data.results; //getContext( 'results' );
	let year     = data.year; // getContext( 'year' );

	state.title = `Leistungen für ${data.district.name}`;
	state.menu = {
		trail : [
			{ name:'Home',      href:'/' },
			{ name:'Obmann',    href:'/moderator' },
			{ name: data.district.short,   href:`/moderator/${data.district.id}` },
			{ name:'Leistungen', href:`/moderator/${data.district.id}/result/${data.year}` },
		],
		options : [],
	};

</script>

<Results { data } />

