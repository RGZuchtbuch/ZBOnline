<script>
	import {getContext, setContext} from 'svelte';
	import {txt } from '$lib/js/toolbox.js';
	import Pair from '$lib/cmp/pair/Pair.svelte';

	let breeder  = getContext( 'breeder' );
	let district = getContext( 'district' );
	let page     = getContext( 'page' );
	let pair     = getContext( 'pair' );
	let standard = getContext( 'standard' );

	if( district && breeder && pair ) {
		page.title = `Stamm ${pair.year}.${pair.name} von Züchter ${txt(breeder.firstname)} ${ txt(breeder.infix) } ${ txt(breeder.lastname) }`;
		page.menu.trail = [
			{ name:'Home',      href:'/' },
			{ name:'Obmann',    href:'/moderator' },
			{ name:'Verband',   href:`/moderator/${district.id}` },
			{ name:'Züchter',   href:`/moderator/${district.id}/breeder/${breeder.id}` },
			{ name:'Stämme',    href:`/moderator/${district.id}/breeder/${breeder.id}/pair` },
			{ name:''+pair.year%100+'.'+pair.name, href:`/moderator/${district.id}/breeder/${breeder.id}/pair/${pair.id}` },
		];
		page.menu.options = [
		]

	}

</script>


<Pair {pair} />
