<script>
	import { onMount } from 'svelte';
	import { fade } from 'svelte/transition';
	import { page } from '$app/state';
	import { cfg, ctx, dirty } from '$lib/js/store.svelte.js';
	import { fullName, shortName, txt } from '$lib/js/tools.js';
	import model from '$lib/js/model.js';

	import Pairs from '$lib/cmp/breeder/Pairs.svelte';

	let mounted = $state( false );

	$effect( async () => {
		if( ctx.breeder && dirty.pairs && page.url ) await loadPairs( ctx.breeder.id );
	})

	$effect(async () => {
		if( ctx.district && ctx.breeder && ctx.pairs ) setHeader();
	})

	async function loadPairs( breederId ) {
		ctx.pairs = null;
		ctx.pairs = await model.Pair.query( { breeder:breederId } );
	}

	function setHeader() {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.short}, Leistungen, Züchtermeldungen ${fullName(ctx.breeder)}`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: 'Verbände', href:`/moderator/district`},
			{name: 'Verband', href:`/moderator/district/${ctx.district.id}`},
			{name: 'Leistungen', href: `/moderator/district/${ctx.district.id}/result`},
			{name: 'Züchter', href: `/moderator/district/${ctx.district.id}/result/breeder`},
			{name: fullName( ctx.breeder ) },
		];

	}

	onMount( () => mounted = true );
</script>

{#if ctx.breeder && ctx.pairs && mounted }
	<main in:fade={{duration:cfg.fadeIn}}>
		<Pairs breeder={ctx.breeder} pairs={ctx.pairs} />
	</main>
{/if}



