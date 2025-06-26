<script>
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import {txt } from '$lib/js/toolbox.js';
	import { Breeder } from '$lib/js/breeder.svelte.js';
	import { District } from '$lib/js/model/federation.svelte.js';
	import { Pair } from '$lib/js/pair.svelte.js';
	import PairCmp from '$lib/cmp/pair/Pair.svelte';

	let breeder  = $state( null );
	let district = $state( null );
	let pair     = $state( null );

	$effect( async () => {

		const data = await Promise.all( [
			//Breeder.load(+page.params.breeder),
			District.load(+page.params.district),
			Pair.load( +page.params.pair, +page.params.breeder, +page.params.district ) // breeder for creating new
		] );
		if( data ) {
			console.log( 'Data', data );
			//breeder  = data[0];
			district = data[0];
			pair     = data[1];
			console.log( data[1] );
		}
		setHeader();
	});

	function setHeader() {
		console.log( 'SetHeader');
		const title = `Stamm ${pair.year}.${pair.name} von Züchter ${txt(pair.breeder.firstname)} ${ txt(pair.breeder.infix) } ${ txt(pair.breeder.lastname) }`;
		console.log( 'SetHeader B');
		const menu = {
			trail : [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: district.short,  href:`/moderator/${pair.districtId}` },
				{name: 'Züchter', href: `/moderator/${pair.districtId}/breeder`},
//				{name:`${breeder.firstname.charAt(0)}.${breeder.lastname.charAt(0)}`, href:`/moderator/${pair.districtId}/breeder/${pair.breederId}` },
				{name:`${pair.breeder.short}`, href:`/moderator/${pair.districtId}/breeder/${pair.breederId}` },
				{name: 'Stämme', href: `/moderator/${pair.districtId}/breeder/${pair.breederId}/pair`},
				{
					name: '' + pair.year % 100 + '.' + pair.name,
					href: `/moderator/${pair.districtId}/breeder/${pair.breederId}/pair/${pair.id}`
				},
			],
			options : [],
		}
		console.log( 'SetHeader C');
		store.title = title; // to set after loading
		store.menu  = menu;
	}

</script>


<PairCmp {pair} />
