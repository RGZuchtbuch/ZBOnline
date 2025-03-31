<script>
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import {txt } from '$lib/js/toolbox.js';
	import { Breeder } from '$lib/js/breeder.svelte.js';
	import { District } from '$lib/js/federation.svelte.js';
	import { Pair } from '$lib/js/pair.svelte.js';
	import PairCmp from '$lib/cmp/pair/Pair.svelte';

	// let breeder  = getContext( 'breeder' );
	// let district = getContext( 'district' );
	// let page     = getContext( 'page' );
	// let pair     = getContext( 'pair' );
	// let standard = getContext( 'standard' );

	let breeder  = $state( null );
	let district = $state( null );
	let pair     = $state( null );

	$effect( async () => {
		const data = await Promise.all( [Breeder.load(+page.params.breeder), District.load(+page.params.district), Pair.load( +page.params.pair )] );
		if( data ) {
			breeder  = data[0];
			district = data[1];
			pair     = data[2];
		}
		setHeader();
	});

	function setHeader() {
		const title = `Stamm ${pair.year}.${pair.name} von Züchter ${txt(breeder.firstname)} ${ txt(breeder.infix) } ${ txt(breeder.lastname) }`;
		const menu = {
			trail : [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: 'Verband', href: `/moderator/${district.id}`},
				{name: 'Züchter', href: `/moderator/${district.id}/breeder/${breeder.id}`},
				{name: 'Stämme', href: `/moderator/${district.id}/breeder/${breeder.id}/pair`},
				{
					name: '' + pair.year % 100 + '.' + pair.name,
					href: `/moderator/${district.id}/breeder/${breeder.id}/pair/${pair.id}`
				},
			],
			options : [],
		}
		store.title = title; // to set after loading
		store.menu  = menu;
	}

</script>


<PairCmp {pair} />
