<script>
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import { addCrumb, fullName, shortName, txt } from '$lib/js/tools.js';
	import Pairs from '$lib/cmp/breeder/Pairs.svelte';
	import model from '$lib/js/model.js';

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
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.name}, Züchter ${fullName(ctx.breeder)}, Stämme`;
		ctx.submenu = [
//			{name: 'Stämme', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/pair`},
			{name: 'Mitglied', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/profile`},
		];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: ctx.district.short, href: `/moderator/${ctx.district.id}`},
			{name: 'Züchter', href: `/moderator/${ctx.district.id}/breeder`},
			{name: `${shortName(ctx.breeder)}`, href:`/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}`},
			{name: 'Stämme' },
		];

	}


</script>

Pairs
{#if ctx.breeder && ctx.pairs}
	<Pairs breeder={ctx.breeder} pairs={ctx.pairs} />
{/if}



