<script>
	import {page} from '$app/state';

	import { ctx } from '$lib/js/store.svelte.js';

	import System from '$lib/cmp/admin/System.svelte';

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

		ctx.title = 'Admin: System verwaltung';
		ctx.submenu = [
//			{ name:'Settings', href:'/admin/setting' },
//			{ name:'Logs', href:'/admin/log' },
		];
		ctx.crumbs = [
			//{ name:'Start',    href:'/' },
			{ name:'Admin',    href:'/admin' },
			{ name:'System', href:'/admin/system' },
		];

	}

</script>

<System />



