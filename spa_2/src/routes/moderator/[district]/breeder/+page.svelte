<script>

	import {page} from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import { Breeder } from '$lib/js/breeder.svelte.js';
	import { District } from '$lib/js/model/federation.svelte.js';
	import BreedersCmp from '$lib/cmp/moderator/district/Breeders.svelte';

	//let { breeders } = $props();
	//let state = getContext( 'state' );

	let breeders = $state( null );
	let district = $state( null );


	$effect( async () => {
		district = await District.load( +page.params.district );
		breeders = await Breeder.query( { district:+page.params.district } );
		setHeader();
	})

	// async function load( page ) {
	// 	const response = await api.breeder.get( { districtId:page.params.districtId} );
	// 	breeders = response.breeders;
	// 	setHeader();
	// }

	function setHeader() {
		const title = `Züchter im ${district.name}`;
		const menu = {
			trail: [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: district.short, href: `/moderator/${district.id}` },
				{name: 'Züchter', href: page.url.href },
			],
			options: [],
		}
		store.title = title; // to set after loading
		store.menu  = menu;
	}
</script>

{#if breeders}
	<BreedersCmp {breeders} {district} />
{/if}


