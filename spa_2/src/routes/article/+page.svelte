<script>
	import {onMount} from 'svelte';
	import { page } from '$app/state';
	import { fade, fly, slide } from 'svelte/transition';

	import {cfg, ctx, dirty} from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Articles from '$lib/cmp/article/Articles.svelte';
	import Article from '$lib/cmp/article/Article.svelte';


	$effect( async () => {
		if ( dirty.articles ) ctx.articles = await model.Article.query();
		setHeader();
	})

	// $effect( async () => {
	// 	if( ctx.articles ) setHeader();
	// })

	// async function loadArticles() {
	// 	ctx.articles = await model.Article.query();
	// }

	function setHeader() {
		//ctx.menustate[ '/article' ] = page.url.href;
		ctx.title = `Infos zum BDRG Zuchtbuch [${ctx.articles.length}]`;
		ctx.submenu = [];
		ctx.crumbs = [
			//{name: 'Start', href: '/'},
			{name: 'Infos', href: '/article'},
		];
	}

</script>

<section in:fade={{duration:cfg.fadeIn}}>
	<Articles articles={ctx.articles} />
</section>

