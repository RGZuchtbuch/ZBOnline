<script>
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import { cfg, ctx, dirty } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import { ArgsBuilder, activeYear } from '$lib/js/tools.js';
	import {onMount} from 'svelte';

	import Breeders from '$lib/cmp/result/Breeders.svelte'

	let mounted = $state( false );

//	let args = $derived( getArgs( page ) )

	$effect( async () => {
		if( dirty.breeders && page.url ) await loadBreeders( +page.params.district );
	})

	$effect( async () => {
		if( ctx.district && page.url ) setHeader();
	})

	async function loadBreeders( districtId ) {
		ctx.breeders = null;
		ctx.breeders = await model.Breeder.query( { district:districtId } );
	}

	function setHeader() {
		ctx.menustate[ '/admin' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.short}, Leistungen, Züchter`;
		ctx.submenu = [
//			{name: 'Eingaben', href: `/moderator/district/${ctx.district.id}/result?year=${ctx.year}`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Admin', href: '/admin'},
			{name: 'Verbände', href:`/admin/district`},
			{name: 'Verband', href:`/admin/district/${ctx.district.id}`},
			{name: 'Leistungen', href: `/admin/district/${ctx.district.id}/result?year=${ctx.year}`},
			{name: 'Züchter'},
		];
	}

	onMount( () => { mounted = true })

</script>



{#if ctx.breeders && ctx.district}
	<main in:fade={{duration:cfg.fadeIn}}>
		<Breeders breeders={ctx.breeders} district={ctx.district} />
	</main>
{/if}
