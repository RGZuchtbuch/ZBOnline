<script>
	import {getContext} from 'svelte';
	import {page} from '$app/state';
	import Breeders from '$lib/cmp/moderator/district/Breeders.svelte';
	import api from '$lib/js/api.js.obs';
	import store, { federation } from '$lib/js/store.svelte.js';

	//let { breeders } = $props();
	//let state = getContext( 'state' );

	let breeders = $state( null );
	let district = $derived( $federation.districts[ +page.params.districtId ] );


	$effect( () => {
		load( page )
	})

	async function load( page ) {
		const response = await api.breeder.get( { districtId:page.params.districtId} );
		breeders = response.breeders;
		setHeader();
	}

	function setHeader() {
		const title = `Züchter im ${district.short}`;
		const menu = {
			trail: [
				{name: 'Home', href: '/'},
				{name: 'Obmann', href: '/moderator'},
				{name: district.short, href: `/moderator/${district.id}` },
				{name: 'Züchter', href: page.url.href },
			],
			options: [],
		}
		store.title.update(() => title); // to set after loading
		store.menu.update(() => menu);
	}
</script>

{#if breeders}
	<Breeders {breeders} {district} />
{/if}


