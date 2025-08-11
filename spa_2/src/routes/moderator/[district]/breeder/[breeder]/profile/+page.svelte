<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import Profile from '$lib/cmp/breeder/profile.svelte';
	import { fullName, shortName } from '$lib/js/tools.js';



	$effect( async () => {
		if( ctx.breeder ) setHeader();
	});


	function setHeader() {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Verband ${ctx.district.name}, Züchter ${fullName(ctx.breeder)}`;
		ctx.submenu = [
			{name: 'Stämme', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/pair`},
//			{name: 'Mitglied', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/profile`},
		];
		ctx.crumbs = [
			{name: 'Start', href: '/'},
			{name: 'Obmann', href: '/moderator'},
			{name: ctx.district.short, href: `/moderator/${ctx.district.id}`},
			{name: 'Züchter', href: `/moderator/${ctx.district.id}/breeder`},
			{name: `${shortName(ctx.breeder)}`, href:`/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}`},
			{name: 'Mitglied' },
		];

		// ctx.header = {
		// 	title: `Mitgliedsdaten für Züchter ${ fullName( ctx.breeder )}`,
		// 	menu: {
		// 		trail: [
		// 			{name: 'Home', href: '/'},
		// 			{name: 'Obmann', href: '/moderator'},
		// 			{name: ctx.district.short, href: `/moderator/${ctx.district.id}`},
		// 			{name: 'Züchter', href: `/moderator/${ctx.district.id}/breeder`},
		// 			{
		// 				name: shortName( ctx.breeder ),
		// 				href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}`,
		// 			},
		// 			{name: 'Mitglied' },
		// 		],
		// 		options: [
		// 			{name: 'Stämme', href: `/moderator/${ctx.district.id}/breeder/${ctx.breeder.id}/pair`},
		// 		],
		// 	}
		// }
	};



</script>

<Profile breeder={ctx.breeder} district={ctx.district} />
