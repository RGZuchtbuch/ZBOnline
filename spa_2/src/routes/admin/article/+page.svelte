<script>
	import { page } from '$app/state';
	import { cfg, ctx, dirty } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Articles from '$lib/cmp/admin/article/Articles.svelte';

	$effect( async () => {
		if( dirty.articles || page.url ) await load();
	})
	$effect( () => {
		if( true ) setHeader();
	})

	async function load() {
		console.log( 'Load Articles' );
		//dirty.articles = false;
		ctx.articles = null;
		ctx.articles = await model.Article.query();
	}

	function setHeader() {
		ctx.menustate[ '/admin' ] = page.url.href;

		ctx.title = 'Admin: Infos zum Bearbeiten';
		ctx.submenu = [
			//{ name:'Settings', href:'/admin/setting' },
			//{ name:'Logs', href:'/admin/log' },
		];
		ctx.crumbs = [
			//{ name:'Start',    href:'/' },
			{ name:'Admin',    href:'/admin' },
			{ name:'Infos', href:'/admin/article' },
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


<Articles articles={ctx.articles}/>



