<script>
	import {onMount} from 'svelte';
	import { fade } from 'svelte/transition';
	import { page } from '$app/state';
	import {cfg, ctx, dirty} from '$lib/js/store.svelte.js';
	import { fullName } from '$lib/js/tools.js';
	import Pairs from '$lib/cmp/breeder/Pairs.svelte';
	import model from '$lib/js/model.js';

	let mounted = $state( false );

	$effect( async () => {
		if( dirty.pairs && page.url ) await loadPairs( ctx.breeder.id );
	})

	$effect(async () => {
		if( ctx.district && ctx.breeder && ctx.pairs ) setHeader();
	})

	async function loadPairs( breederId ) {
		ctx.pairs = await model.Pair.query( { breeder:breederId } );
	}

	function setHeader() {
		ctx.menustate[ '/admin' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.short}, Züchter ${fullName(ctx.breeder)}, Stämme`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Admin', href: '/admin'},
			{name: 'Verbände', href: `/admin/district/${ctx.district.id}`},
			{name: 'Verband', href: `/admin/district/${ctx.district.id}`},
			{name: 'Züchter', href: `/admin/district/${ctx.district.id}/breeder`},
			{name: `${fullName(ctx.breeder)}`, href:`/admin/district/${ctx.district.id}/breeder/${ctx.breeder.id}`},
			{name: 'Stämme' },
		];

	}

	onMount( () => mounted = true );
</script>

{#if ctx.breeder && ctx.pairs && mounted }
	<main in:fade={{duration:cfg.fadeIn}}>
		<Pairs breeder={ctx.breeder} pairs={ctx.pairs} />
	</main>
{/if}



