<script>
	import { ctx } from '$lib/js/store.svelte.js';
    import Districts from '$lib/cmp/moderator/Districts.svelte';
	import {page} from '$app/state';

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

		ctx.title = 'Admin: System settings';
		ctx.submenu = [
//			{ name:'Settings', href:'/admin/setting' },
//			{ name:'Logs', href:'/admin/log' },
		];
		ctx.crumbs = [
			//{ name:'Start',    href:'/' },
			{ name:'Admin',    href:'/admin' },
			{ name:'Settings', href:'/admin/setting' },
		];
		// ctx.header = {
		// 	title: 'Admin: System settings',
		// 	menu: {
		// 		trail : [
		// 			{ name:'Start',    href:'/' },
		// 			{ name:'Admin',    href:'/admin' },
		// 			{ name:'Settings' },
		// 		],
		// 		options : [
		// 			{ name:'Verbände', href:'/admin/district' },
		// 			//{ name:'Settings', href:'/admin/setting' },
		// 			{ name:'Logs', href:'/admin/log' },
		// 		],
		// 	}
		// }
	}

</script>

System settings, TODO



