<script>
	import { page } from '$app/state';
	import { app } from '$lib/js/store.svelte.js';
	import api from '$lib/js/api.js';
	import {txt } from '$lib/js/toolbox.js';
	import Pair from '$lib/cmp/pair/Pair.svelte';
	import {onMount} from 'svelte';

	let { data } = $props();

	let standard = $state( data.standard ); // make reactive
	let district = $state( data.district );
	let breeder  = $state( data.breeder );
	let pair     = $state( data.pair );



	if( district && breeder && pair ) {
		app.title = `Stamm ${pair.year}.${pair.name} von Züchter ${txt(breeder.firstname)} ${ txt(breeder.infix) } ${ txt(breeder.lastname) }`;
		app.menu.trail = [
			{ name:'Home',      href:'/' },
			{ name:'Obmann',    href:'/moderator' },
			{ name:'Verband',   href:`/moderator/${district.id}` },
			{ name:'Züchter',   href:`/moderator/${district.id}/breeder/${breeder.id}` },
			{ name:'Stämme',    href:`/moderator/${district.id}/breeder/${breeder.id}/pair` },
			{ name:''+pair.year%100+'.'+pair.name, href:`/moderator/${district.id}/breeder/${breeder.id}/pair/${pair.id}` },
		];
		app.menu.options = [
		]

	}

</script>


<Pair {pair} {standard} />
