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
//		if( dirty.pairs || page.url ) await loadPairs( +page.params.breeder );
		//if( dirty.pairs || page.url ) await loadPairs( ctx.user.id );
		if( dirty.pairs || page.url ) await loadPairs( ctx.user.id );
	})

	$effect(async () => {
		if( ctx.district && ctx.breeder && ctx.pairs ) {
			setHeader();
			//addCrumb( { name:'Stämme', url:page.url } );
		}
	})

	async function loadPairs( breederId ) {
		console.log( 'Load breeder pairs' );
		//ctx.pairs = null;
		//dirty.pairs = false; // no need as I do dirty.pairs++ in pair submit
		ctx.pairs = await model.Pair.query( { breeder:breederId } );
	}

	function setHeader() {
		ctx.menustate[ '/breeder' ] = page.url.href;


		ctx.title = `Züchter ${fullName(ctx.breeder)}, Stämme`;
		ctx.submenu = [
//			{name: 'Stämme', href: `/breeder/pair`},
			//{name: 'Mitglied', href: `/breeder/profile`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: `Züchter`, href: `/breeder` },
			{name: 'Stämme'},
		];
	}
	onMount( () => mounted = true );

</script>


{#if ctx.breeder && ctx.pairs && mounted}
	<main in:fade={{duration:cfg.fadeIn}}>
		<div class='text-xs text-center italic'>
			Stämme für {ctx.breeder.firstname}
		</div>

		<Pairs breeder={ctx.breeder} pairs={ctx.pairs}  />
	</main>
{/if}
