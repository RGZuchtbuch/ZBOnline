<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import {page} from '$app/state';
	import Admin from '$lib/cmp/admin/Admin.svelte';

	$effect( () => {
		ctx.menustate[ '/admin' ] = page.url.href;
		ctx.title = 'Admin';
		ctx.submenu = [
			{ name:'Infos', href:'/admin/article' },
			{ name:'Verbände', href:'/admin/district' },
			{ name:'Standard', href:'/admin/standard' },
			{ name:'System', href:'/admin/system' },
			{ name:'Logs', href:'/admin/log' },
		];
		ctx.crumbs = [
			//{ name:'Start',    href:'/' },
			{ name:'Admin',    href:'/admin' },
		];
	})

</script>

<Admin />



