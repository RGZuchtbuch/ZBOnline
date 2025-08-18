<script>
	import { ctx } from '$lib/js/store.svelte.js';
    import Districts from '$lib/cmp/moderator/Districts.svelte';
	import {page} from '$app/state';

	$effect( () => {
		ctx.menustate[ '/admin' ] = page.url.href;
		ctx.title = 'Admin';
		ctx.submenu = [
			{ name:'Infos', href:'/admin/article' },
			{ name:'Verbände', href:'/admin/district' },
			{ name:'Standard', href:'/admin/standard' },
			{ name:'Setting', href:'/admin/setting' },
			{ name:'Logs', href:'/admin/log' },
		];
		ctx.crumbs = [
			//{ name:'Start',    href:'/' },
			{ name:'Admin',    href:'/admin' },
		];
		// ctx.header = {
		// 	title: 'Admin',
		// 	menu: {
		// 		trail : [
		// 			{ name:'Start',    href:'/' },
		// 			{ name:'Admin' },
		// 		],
		// 		options : [
		// 			{ name:'Verbände', href:'/admin/district' },
		// 			{ name:'Setting', href:'/admin/setting' },
		// 			{ name:'Logs', href:'/admin/log' },
		// 		],
		// 	}
		// }
	})

</script>

Alles für den Admin



