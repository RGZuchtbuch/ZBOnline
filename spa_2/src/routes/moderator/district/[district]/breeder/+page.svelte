<script>

	import {page} from '$app/state';
	import { fade } from 'svelte/transition';
	import {cfg, ctx, dirty} from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';


	import Breeders from '$lib/cmp/moderator/district/Breeders.svelte';

	$effect( async () => {
		console.log('Load Breeders', dirty.breeders, page.url.href );
		const districtId = +page.params.district;
		if( dirty.breeders && page.url ) await loadBreeders( districtId );
	})

	$effect( async () => {
		if( ctx.district && ctx.breeders ) setHeader();
	})

	async function loadBreeders( districtId ) {
//		console.log( 'load district breeders' );
//		ctx.breeders = null;
		ctx.breeders = await model.Breeder.query( { district:districtId } );
	}

	function setHeader() {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.name}, Züchter`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: 'Verbände', href: `/moderator/district/${ctx.district.id}`},
			{name: 'Verband', href: `/moderator/district/${ctx.district.id}`},
			{name: 'Züchter'},
		];
	}

</script>


{#if ctx.breeders && ctx.district}
	<main in:fade={{duration:cfg.fadeIn}}>
		<Breeders breeders={ctx.breeders} district={ctx.district} />
	</main>
{/if}


