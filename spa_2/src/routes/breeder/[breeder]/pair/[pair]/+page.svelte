<script>
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import {fullName, shortName} from '$lib/js/tools.js';
	import Pair from '$lib/cmp/pair/Pair.svelte';
	import model from '$lib/js/model.js';
	import {onMount} from 'svelte';

	console.log( "pair", +page.params.pair );

	//ctx.pair = null;

	$effect( async () => {
		//console.log("+page, Load pair", +page.params.pair )
		let id = +page.params.pair;
		if( dirty.pair || ( ctx.pair && ctx.pair.id !== id ) ) await loadPair( id )//ctx.pair = await load( +page.params.pair );
	});

	$effect( async () => {
		if (ctx.breeder && ctx.pair) setHeader();
	});


	// onMount( async () => {
	// 	ctx.pair = await load( +page.params.pair );
	// })


	async function loadPair( id ) {
		console.log( 'Load Pair' );
		dirty.pair = false;
		let pair = null;
		if( id === 0 ) {
			pair = await model.Pair.new( ctx.breeder ); // new for this breeder
		} else {
			const response = await Promise.all([
				model.Pair.load( id, ctx.breeder) // breeder for info
			]);
			pair = response[0];
		}
		ctx.pair = pair;
	}

	function setHeader() {
		ctx.header = {
			title: `Stamm ${ctx.pair.year % 100}.${ctx.pair.name} von Züchter ${fullName(ctx.breeder)}`,
			menu: {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Züchter', href: '/breeder'},
					{name: shortName(ctx.breeder), href: `/breeder/${ctx.breeder.id}`},
					{name: 'Stämme', href: `/breeder/${ctx.pair.breederId}/pair`},
					{name: `${ctx.pair.year % 100}.${ctx.pair.name}`},
				],
				options: [],
			}
		}

	}

</script>

{#if ctx.federation && ctx.standard && ctx.breeder && ctx.pair }
	<Pair breeder={ctx.breeder} pair={ctx.pair} />
{/if}
