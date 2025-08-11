<script>
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import { addCrumb, fullName, shortName } from '$lib/js/tools.js';
	import Pair from '$lib/cmp/pair/Pair.svelte';
	import model from '$lib/js/model.js';

	$effect( async () => {
		if( dirty.pair || page.url ) await loadPair( +page.params.pair );
	})

	$effect( async () => {
		if( ctx.district && ctx.breeder && ctx.pair ) setHeader();
	});

	async function loadPair( id ) {
		dirty.pair = false;
		ctx.pair = null;
		ctx.pair = await model.Pair.load( +page.params.pair, +page.params.breeder, +page.params.district ) // breeder for creating new
	}

	function setHeader() {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.name}, Züchter ${fullName(ctx.breeder)}, Stämm ${ctx.pair.year % 100}.${ctx.pair.name}`;
		ctx.submenu = [
//			{name: 'Stämme', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/pair`},
//			{name: 'Mitglied', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/profile`},
		];
		ctx.crumbs = [
			{name: 'Start', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: ctx.district.short, href: `/moderator/${ctx.district.id}`},
			{name: 'Züchter', href: `/moderator/${ctx.district.id}/breeder`},
			{name: `${shortName(ctx.breeder)}`, href:`/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}`},
			{name: 'Stämme', href: `/moderator/${ctx.pair.districtId}/breeder/${ctx.pair.breederId}/pair`},
			{name: `${ctx.pair.year % 100}.${ctx.pair.name}`},
		];

		// ctx.header = {
		// 	title: `Stamm ${ctx.pair.year % 100}.${ctx.pair.name} von Züchter ${fullName(ctx.breeder)}`,
		// 	menu: {
		// 		trail: [
		// 			{name: 'Home', href: '/'},
		// 			{name: 'Obmann', href: '/moderator'},
		// 			{name: ctx.district.short, href: `/moderator/${ctx.pair.districtId}`},
		// 			{name: 'Züchter', href: `/moderator/${ctx.pair.districtId}/breeder`},
		// 			//				{name:`${breeder.firstname.charAt(0)}.${breeder.lastname.charAt(0)}`, href:`/moderator/${pair.districtId}/breeder/${pair.breederId}` },
		// 			{
		// 				name: `${shortName( ctx.breeder )}`,
		// 				href: `/moderator/${ctx.pair.districtId}/breeder/$ctx.{pair.breederId}`
		// 			},
		// 			{name: 'Stämme', href: `/moderator/${ctx.pair.districtId}/breeder/${ctx.pair.breederId}/pair`},
		// 			{name: `${ctx.pair.year % 100}.${ctx.pair.name}`},
		// 		],
		// 		options: [],
		// 	}
		// }
	}


</script>

Pair
{#if ctx.pair}kl
	<Pair pair={ctx.pair} />
{/if}