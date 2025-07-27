<script>
	import { page } from '$app/state';
	import { ctx, flag } from '$lib/js/store.svelte.js';
	import { fullName } from '$lib/js/tools.js';
	import Pair from '$lib/cmp/pair/Pair.svelte';
	import model from '$lib/js/model.js';

	console.log( "pair", +page.params.pair );

	ctx.pair = null;
	ctx.breeder = null;

	$effect( async () => {
		console.log("+page, Load pair", +page.params.pair )
		ctx.pair = await loadPair( +page.params.pair );
//	});
//	$effect( async () => {
		console.log('Load Breeder to Pair' );
		//ctx.breeder = await model.Breeder.load( ctx.pair.breederId ); // breeder for info
//	})

//	$effect( async () => {
		console.log('Set header' );
		if (ctx.breeder && ctx.pair) {
			ctx.header = {
				title: `Stamm ${ctx.pair.year}.${ctx.pair.name} von Züchter ${fullName(ctx.breeder)}`,
				menu: {
					trail: [
						{name: 'Home', href: '/'},
						{name: `${ctx.breeder.short}`, href: `/breeder/${ctx.pair.breederId}`},
						{name: 'Stämme', href: `/breeder/${ctx.pair.breederId}/pair`},
						{name: `${ctx.pair.year % 100}.${ctx.pair.name}`},
					],
					options: [],
				}
			}
		}
	});


	async function loadPair( id ) {
		let pair = null;
		if( id === 0 ) {
			pair = await model.Pair.new( ctx.breeder ); // new for this breeder
		} else {
			const response = await Promise.all([
				model.Pair.load( id, ctx.breeder) // breeder for info
			]);
			pair = response[0];
		}
		return pair;
	}
</script>

{#if ctx.federation && ctx.standard && ctx.breeder && ctx.pair }
	<Pair breeder={ctx.breeder} pair={ctx.pair} />
{/if}
