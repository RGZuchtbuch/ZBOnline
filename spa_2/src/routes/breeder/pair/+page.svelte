<script>
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import {cfg, ctx, dirty} from '$lib/js/store.svelte.js';
	import { fullName, shortName} from '$lib/js/tools.js';
	import Pairs from '$lib/cmp/breeder/Pairs.svelte';
	import model from '$lib/js/model.js';
	import {onMount} from 'svelte';

	let mounted = $state( false );

	$effect( async () => {
		if( dirty.pairs || page.url ) await loadPairs( ctx.user.id );
	})

	$effect(async () => {
		if( ctx.district && ctx.breeder && ctx.pairs ) {
			setHeader();
		}
	})

	async function loadPairs( breederId ) {
		ctx.pairs = null;
		ctx.pairs = await model.Pair.query( { breeder:breederId } );
	}

	function setHeader() {
		ctx.menustate[ '/breeder' ] = page.url.href;
		ctx.title = `Züchter ${fullName(ctx.breeder)}, Stämme`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			{name: `Züchter`, href: `/breeder` },
			{name: 'Stämme'},
		];
	}
	onMount( () => mounted = true );

</script>


{#if ctx.breeder && ctx.pairs && mounted}
	<section in:fade={{duration:cfg.fadeIn}}>
		<Pairs breeder={ctx.breeder} bind:pairs={ctx.pairs}  />
	</section>
{/if}
