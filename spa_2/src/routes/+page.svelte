<script>
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';
	import { cfg, ctx, dirty } from '$lib/js/store.svelte.js'
	import model from '$lib/js/model.js';
	import { addCrumb } from '$lib/js/tools.js';

	import Article from '$lib/cmp/article/Article.svelte';

	$effect( async () => {
		if( true ) await loadArticle( 1 );
	})
	$effect( () => {
		if( page.url ) setHeader();
	})

	async function loadArticle( id ) {
		console.log("load Article", id )
		dirty.article = false;
		ctx.article = null;
		ctx.article = await model.Article.load( id );
	}

	function setHeader() {
		ctx.title = 'Das BDRG Zuchtbuch';
		ctx.submenu = [];
		ctx.crumbs = [
			//{name: 'Gast', href: '/'}
		];
	}



</script>

<main class='' in:fade={{duration:cfg.fadeIn}}>
	<Article article={ctx.article}/>
</main>
