<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import Profile from '$lib/cmp/breeder/profile.svelte';
	import {addCrumb, fullName, shortName, txt} from '$lib/js/tools.js';

	console.log( ctx.breeder, ctx.district );

	$effect( async () => {
		if( ctx.breeder ) setHeader();
	} );

	function setHeader() {
		ctx.menustate[ '/breeder' ] = page.url.href;


		ctx.title = `Züchter ${fullName(ctx.breeder)}, Mitglied`;
		ctx.submenu = [
//			{name: 'Stämme', href: `/breeder/pair`},
			//{name: 'Mitglied', href: `/breeder/profile`},
		];
		ctx.crumbs = [
			{name: 'Start', href: '/'},
			{name: `Züchter ${shortName(ctx.breeder)}`, href: `/breeder` },
			{name: 'Mitglied'},
		];
// 		ctx.header = {
// 			title: `Mitgliedsdaten für Züchter ${fullName(ctx.breeder)}`,
// 			menu: {
// 				trail: [
// 					{name: 'Start', href: '/'},
// //					{name: `Züchter ${shortName(ctx.breeder)}`, href: `/breeder/${ctx.breeder.id}` },
// 					{name: `Züchter ${shortName(ctx.breeder)}`, href: `/breeder` },
// 					{name: 'Mitglied'},
// 				],
// 				options: [
// //					{name: 'Stämme', href: `/breeder/${ctx.breeder.id}/pair`},
// 					{name: 'Stämme', href: `/breeder/pair`},
// 				],
// 			}
// 		}
	}



</script>

{#if ctx.breeder && ctx.district }
	<Profile breeder={ctx.breeder} district={ctx.district} />
{/if}
