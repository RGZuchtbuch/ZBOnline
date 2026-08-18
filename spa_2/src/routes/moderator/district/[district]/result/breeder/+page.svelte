<script>
import { onMount } from 'svelte';
import { page } from '$app/state';
import { fade } from 'svelte/transition';
import { cfg, ctx, dirty } from '$lib/js/store.svelte.js';
import model from '$lib/js/model.js';

import Breeders from '$lib/cmp/result/Breeder.svelte'

let mounted = $state( false );

let breeders = $state( [] ); // empty pairs

//	let args = $derived( getArgs( page ) )

	$effect( async () => {
//		if( dirty.breeders && page.url ) await loadBreeders( +page.params.district );
		if( page.url ) await loadBreedersPairs( +page.params.district, ctx.year );
	})

	$effect( async () => {
		if( ctx.district && page.url ) setHeader();
	})

	async function loadBreeders( districtId ) {
		ctx.breeders = null;
		ctx.breeders = await model.Breeder.query( { district:districtId } );
	}

	async function loadBreedersPairs( districtId, year ) {
		breeders = await model.Result.loadBreedersResults( districtId, year );
	}

	function setHeader() {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.short}, Eingabe Zuchtleistungen ${ctx.year}`;
		ctx.submenu = [
//			{name: 'Eingaben', href: `/moderator/district/${ctx.district.id}/result?year=${ctx.year}`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: 'Verbände', href:`/moderator/district`},
			{name: 'Verband', href:`/moderator/district/${ctx.district.id}`},
			{name: 'Leistungen', href: `/moderator/district/${ctx.district.id}/result?year=${ctx.year}`},
			{name: 'Züchter'},
		];
	}

	onMount( () => { mounted = true })

</script>


{#if ctx.district && breeders }
	<main in:fade={{duration:cfg.fadeIn}}>
		<Breeders breeders={ breeders } district={ctx.district} />
	</main>
{/if}
