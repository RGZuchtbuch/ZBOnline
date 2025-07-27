<script>
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import {addCrumb, fullName, shortName} from '$lib/js/tools.js';
	import Pairs from '$lib/cmp/breeder/Pairs.svelte';
	import model from '$lib/js/model.js';

	$effect( async () => {
		if( dirty.pairs || page.url ) await loadPairs( +page.params.breeder );
	})

	$effect(async () => {
		if( ctx.district && ctx.breeder && ctx.pairs ) {
			setHeader();
			addCrumb( { name:'Stämme', url:page.url } );
		}
	})

	async function loadPairs( breederId ) {
		console.log( 'Load breeder pairs' );
		ctx.pairs = null;
		ctx.pairs = await model.Pair.query( { breeder:page.params.breeder } );
	}

	function setHeader() {
		ctx.header = {
			title: `Züchter ${fullName(ctx.breeder)}`,
			menu: {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Züchter', href: '/breeder'},
					{name: shortName(ctx.breeder), href: `/breeder/me/${ctx.breeder.id}`},
					{name: 'Stämme'},
				],
				options: [
					{name: 'Mitglied', href: `/breeder/me/${ctx.breeder.id}/profile`},
				]
			}
		};
	}

</script>
{#if ctx.breeder && ctx.pairs}
	<div class='text-xs text-center italic'>
		Stämme für {ctx.breeder.firstname}
	</div>

	<Pairs breeder={ctx.breeder} pairs={ctx.pairs}  />
{/if}
