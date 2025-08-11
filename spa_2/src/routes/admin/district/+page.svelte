<script>
	import { ctx } from '$lib/js/store.svelte.js';
    import Districts from '$lib/cmp/admin/districts/DistrictTree.svelte';
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

		ctx.title = 'Admin: Verbände zum Verwalten';
		ctx.submenu = [
			//{ name:'Settings', href:'/admin/setting' },
			//{ name:'Logs', href:'/admin/log' },
		];
		ctx.crumbs = [
			//{ name:'Start',    href:'/' },
			{ name:'Admin',    href:'/admin' },
			{ name:'Verbände', href:'/admin/district' },
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
		// 			//{ name:'Verbände', href:'/admin/district' },
		// 			{ name:'Settings', href:'/admin/setting' },
		// 			{ name:'Logs', href:'/admin/log' },
		// 		],
		// 	}
		// }
	}

</script>

Districts to moderate as obmann
{#if ctx.federation}
	<Districts root={ctx.federation}/>
{/if}


