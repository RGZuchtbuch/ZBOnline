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
		ctx.articles = null;
		ctx.articles = await model.Article.query();
	}

	function setHeader() {
		ctx.menustate[ '/admin' ] = page.url.href;

		ctx.title = 'Admin: Infos zum Bearbeiten';
		ctx.submenu = [
		];
		ctx.crumbs = [
			{ name:'Admin',    href:'/admin' },
			{ name:'Infos', href:'/admin/article' },
		];
	}

</script>


<Articles articles={ctx.articles}/>



