<script>
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import { addCrumb, fullName, shortName, txt } from '$lib/js/tools.js';
	import Pairs from '$lib/cmp/breeder/Pairs.svelte';
	import model from '$lib/js/model.js';

	addCrumb( { name:'Stämme', href:page.url.href });


	$effect( async () => {
		if( dirty.pairs || page.url ) await loadPairs( +page.params.breeder );
	})

	$effect(async () => {
		if( ctx.district && ctx.breeder && ctx.pairs ) setHeader();
	})

	async function loadPairs( breederId ) {
		dirty.pairs = false;
		ctx.pairs = null;
		ctx.pairs = await model.Pair.query( { breeder:breederId } );
	}

	function setHeader() {
		ctx.header = {
			title :
				ctx.breeder.id===0 ?
					'Neu' :
					`Zuchter ${ fullName( ctx.breeder) } Stämme/Paare`,
			menu : {
				trail: [
					{name: 'Home', href: '/'},
					{name: 'Obmann', href: '/moderator'},
					{name: ctx.district.short, href: `/moderator/${ctx.district.id}`},
					{name: 'Züchter', href: `/moderator/${ctx.district.id}/breeder`},
					{
						name: ctx.breeder.id === 0 ? 'Neu' : shortName( ctx.breeder ),
						href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}`,
					},
					{name: 'Stämme'},
				],
				options: [
					//				{name: 'Stämme', href: '/moderator/' + district.id + '/breeder/' + breeder.id + '/pair'},
					{name: 'Mitglied', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/profile`},
				],
			}
		}
	}


</script>

Pairs
{#if ctx.breeder && ctx.pairs}
	<Pairs breeder={ctx.breeder} district={ctx.district} pairs={ctx.pairs} year={ctx.year} />
{/if}



