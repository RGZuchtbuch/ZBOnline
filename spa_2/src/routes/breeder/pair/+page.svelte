<script>
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import { fullName, shortName} from '$lib/js/tools.js';
	import Pairs from '$lib/cmp/breeder/Pairs.svelte';
	import model from '$lib/js/model.js';

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
// 		ctx.header = {
// 			title: `Züchter ${fullName(ctx.breeder)}`,
// 			menu: {
// 				trail: [
// 					{name: 'Start', href: '/'},
// //					{name: `Züchter ${shortName(ctx.breeder)}`, href: `/breeder/${ctx.breeder.id}` },
// 					{name: `Züchter ${shortName(ctx.breeder)}`, href: `/breeder` },
// 					{name: 'Stämme'},
// 				],
// 				options: [
// //					{name: 'Mitglied', href: `/breeder/${ctx.breeder.id}/profile`},
// 					{name: 'Mitglied', href: `/breeder/profile`},
// 				]
// 			}
// 		};
	}

</script>
{#if ctx.breeder && ctx.pairs}
	<div class='text-xs text-center italic'>
		Stämme für {ctx.breeder.firstname}
	</div>

	<Pairs breeder={ctx.breeder} pairs={ctx.pairs}  />
{/if}
