<script>
	import {onMount} from 'svelte';
	import { page } from '$app/state';
	import { fade } from 'svelte/transition';

	import { cfg, ctx, dirty } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Article from '$lib/cmp/article/Article.svelte';

	$effect( async () => {
		loadArticle(+page.params.article);
	});

	$effect( () => {
		if( ctx.article ) {
			setHeader();
		}
	})

	async function loadArticle( id ) {
		ctx.article = await model.Article.load( id );
	}

	function setHeader() {
		//ctx.menustate[ '/article' ] = page.url.href;
		ctx.title = ctx.article.title ? ctx.article.title : '?';
		ctx.submenu = [];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Infos', href: '/article'},
			{name: ctx.article.title.substring( 0, 20 )+'..' },
		];
	}

</script>

<main in:fade={{duration:cfg.fadeIn}}>
	<Article article={ctx.article} />
</main>

<style>

</style>