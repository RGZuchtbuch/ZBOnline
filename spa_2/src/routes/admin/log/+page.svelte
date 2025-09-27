<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import {page} from '$app/state';

	import Logs from '$lib/cmp/admin/log/Logs.svelte';

	$effect( async () => {
		if( true ) await load();
	})
	$effect( () => {
		if( true ) setHeader();
	})

	async function load() {

	}

	function setHeader() {
		ctx.menustate[ '/admin' ] = page.url.href;

		ctx.title = 'Admin: Logs';
		ctx.submenu = [
//			{ name:'Settings', href:'/admin/setting' },
//			{ name:'Logs', href:'/admin/log' },
		];
		ctx.crumbs = [
			//{ name:'Start',    href:'/' },
			{ name:'Admin',    href:'/admin' },
			{ name:'Logs', href:'/admin/logs' },
		];
		// ctx.header = {
		// 	title: 'Admin: Verbände zum Verwalten',
		// 	menu: {
		// 		trail : [
		// 			{ name:'Start',    href:'/' },
		// 			{ name:'Admin',    href:'/admin' },
		// 			{ name:'Verbände', href:'/admin/district' },
		// 		],
		// 		options : [
		// 			{ name:'Verbände', href:'/admin/district' },
		// 			{ name:'Settings', href:'/admin/setting' },
		// 			//{ name:'Logs', href:'/admin/log' },
		// 		],
		// 	}
		// }
	}

</script>

<Logs />



