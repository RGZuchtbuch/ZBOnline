<script>
	import { page } from '$app/state';
	import { ctx, flag } from '$lib/js/store.svelte.js';
	import {fullName, shortName} from '$lib/js/tools.js';
	import Pairs from '$lib/cmp/breeder/Pairs.svelte';
	import model from '$lib/js/model.js';

	//let { data } = $props();


	$effect( async () => {
		if( ctx.breeder && flag.pairs ) {
			ctx.pairs = await model.Pair.query( { breeder:page.params.breeder } );
		}
		if( ctx.pairs ) {
			ctx.header = {
				title: `Züchter ${fullName(ctx.breeder)}`,
				menu: {
					trail: [
						{name: 'Start', href: '/'},
						{name: 'Züchter', href: '/breeder'},
						{name: shortName(ctx.breeder), href: `/breeder/${ctx.breeder.id}`},
						{name: 'Stämme'},
					],
					options: [
						{name: 'Mitglied', href: `/breeder/${ctx.breeder.id}/profile`},
					]
				}
			};
		}
	} );

</script>

Nothing to do
