<script>

	import {page} from '$app/state';
	import { fade } from 'svelte/transition';
	import {cfg, ctx, dirty} from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';


	import Breeders from '$lib/cmp/moderator/district/Breeders.svelte';

	$effect( async () => {
		const districtId = +page.params.district;
		if( dirty.breeders && page.url ) await loadBreeders( districtId );
	})

	$effect( async () => {
		if( ctx.district && ctx.breeders ) setHeader();
	})

	async function loadBreeders( districtId ) {
		ctx.breeders = null;
		ctx.breeders = await model.Breeder.query( { district:districtId } );
	}

	function setHeader() {
		ctx.menustate[ '/admin' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.name}, Züchter`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Admin', href: '/admin'},
			{name: 'Verbände', href: `/admin/district`},
			{name: 'Verband', href: `/admin/district/${ctx.district.id}`},
			{name: 'Züchter'},
		];
	}

</script>


{#if ctx.breeders && ctx.district}
	<main in:fade={{duration:cfg.fadeIn}}>
		<Breeders breeders={ctx.breeders} district={ctx.district} />
	</main>
{/if}


