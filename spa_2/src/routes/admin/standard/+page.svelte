<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import { addCrumb } from '$lib/js/tools.js';

	import Standard from '$lib/cmp/admin/standard/Standard.svelte';


	$effect( () => {
		if( page.url ) setHeader();
	})

	function setHeader() {
		ctx.menustate[ '/admin' ] = page.url.href;

		ctx.title = 'Admin: Standard zum Verwalten';
		ctx.submenu = [
//			{ name:'Settings', href:'/admin/setting' },
//			{ name:'Logs', href:'/admin/log' },
		];
		ctx.crumbs = [
			//{ name:'Start',    href:'/' },
			{ name:'Admin',    href:'/admin' },
			{ name:'Standard', href:'/admin/standard' },
		];
		// ctx.header = {
		// 	title: 'BDRG Rassegeflügel Standard',
		// 	menu: {
		// 		trail: [
		// 			{name: 'Start', href: '/'},
		// 			{name: 'Standard', href: '/standard'},
		// 		],
		// 		options: [
		// 			{name: 'Beiträge', href: '/article'},
		// 			{name: 'Verbände', href: '/federation'},
		// 			{name: 'Standard', href: '/standard'},
		// 			{name: 'Leistungen', href: '/report'},
		// 		],
		// 	},
		// }
	}
</script>

{#if ctx.standard}
	<Standard standard={ctx.standard}/>
{/if}
