<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import {addCrumb, fullName, shortName} from '$lib/js/tools.js';
	import Districts from '$lib/cmp/moderator/Districts.svelte';

	$effect( () => {
		ctx.menustate[ '/moderator' ] = page.url.href;
		ctx.title = `Züchter ${fullName(ctx.breeder)}`;
		ctx.submenu = [
		];
		ctx.crumbs = [
			{name: 'Start', href: '/'},
			{name: `Obmann` },
		];
		// ctx.header = {
		// 	title: 'Obmann: Verbände zum Verwalten',
		// 	menu: {
		// 		trail: [
		// 			{name: 'Start', href: '/'},
		// 			{name: 'Obmann'},
		// 		],
		// 		options: [],
		// 	},
		// }
	});

</script>

<Districts districts={ctx.districts}/>



