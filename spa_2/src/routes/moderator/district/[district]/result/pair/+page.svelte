<script>
	import { onMount } from 'svelte';
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import { cfg, ctx, dirty } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Pairs from '$lib/cmp/result/Pairs.svelte'

	let mounted = $state( false );

	let pairs = $state( [] ); // empty pairs

//	let args = $derived( getArgs( page ) )

	$effect( async () => {
//		if( dirty.breeders && page.url ) await loadBreeders( +page.params.district );
		if( page.url ) await loadBreedersPairs( +page.params.district, ctx.year );
	})

	$effect( async () => {
		if( ctx.district && page.url ) setHeader();
	})

//	async function loadBreeders( districtId ) {
//		ctx.breeders = null;
//		ctx.breeders = await model.Breeder.query( { district:districtId } );
//	}

	async function loadBreedersPairs( districtId, year ) {
		pairs = await model.Pair.query( { district:districtId, year:year } );
	}

	function setHeader() {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.short}, Eingabe Stammleistungen ${ctx.year}`;
		ctx.submenu = [
//			{name: 'Eingaben', href: `/moderator/district/${ctx.district.id}/result?year=${ctx.year}`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: 'Verbände', href:`/moderator/district`},
			{name: 'Verband', href:`/moderator/district/${ctx.district.id}`},
			{name: 'Leistungen', href: `/moderator/district/${ctx.district.id}/result?year=${ctx.year}`},
			{name: 'Stämme'},
		];
	}

	onMount( () => { mounted = true })

</script>


{#if ctx.district && pairs}
	<main in:fade={{duration:cfg.fadeIn}}>
		<Pairs pairs={pairs} district={ctx.district} />
	</main>
{/if}
