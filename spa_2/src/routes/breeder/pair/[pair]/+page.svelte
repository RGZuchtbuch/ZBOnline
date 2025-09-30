<script>
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import {cfg, ctx, dirty} from '$lib/js/store.svelte.js';
	import {fullName, shortName} from '$lib/js/tools.js';
	import Pair from '$lib/cmp/pair/Pair.svelte';
	import model from '$lib/js/model.js';
	import {onMount} from 'svelte';

	let mounted = $state( false );


	$effect( async () => {
		//if( dirty.pair || ( ctx.pair && ctx.pair.id !== id ) ) await loadPair( id )//ctx.pair = await load( +page.params.pair );
		if( page.url ) await loadPair( +page.params.pair )
	});

	$effect( async () => {
		if (ctx.breeder && ctx.pair) setHeader();
	});


	async function loadPair( id ) {
		ctx.pair = null;
		ctx.pair = id === 0 ?
			await model.Pair.new( ctx.breeder) : // new for this breeder
			await model.Pair.load( id );
	}

	function setHeader() {
		ctx.menustate[ '/breeder' ] = page.url.href;
		ctx.title = `Züchter ${fullName(ctx.breeder)}, Stämm ${ctx.pair.year % 100}.${ctx.pair.name}`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			{name: 'Start', href: '/'},
			{name: `Züchter ${shortName(ctx.breeder)}`, href: `/breeder` },
			{name: 'Stämme', href: `/breeder/pair`},
			{name: `${ctx.pair.year % 100}.${ctx.pair.name}`},
		];
	}
	onMount( () => mounted = true );

</script>


{#if ctx.federation && ctx.standard && ctx.breeder && ctx.pair && mounted}
	<main in:fade={{duration:cfg.fadeIn}}>
		<Pair breeder={ctx.breeder} pair={ctx.pair} />
	</main>
{/if}
