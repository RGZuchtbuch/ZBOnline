<script>
	import { page } from '$app/state';
	import { fade, slide } from 'svelte/transition';
	import { cfg, ctx, dirty } from '$lib/js/store.svelte.js'
	import model from '$lib/js/model.js';

	import Article from '$lib/cmp/article/Article.svelte';
	import Home from '$lib/cmp/Home.svelte';
	import {onMount} from 'svelte';

	let articles = $state( null );

	onMount( async () => {
		articles = await loadArticles();
	})

	$effect( async () => {
		if( true ) await loadArticle( 1 );
	})
	$effect( () => {
		if( page.url ) setHeader();
	})

	async function loadArticle( id ) {
		ctx.article = await model.Article.load( id );
	}

	async function loadArticles() {
		return Promise.all( [
			model.Article.load( 2 ), model.Article.load( 3 ), model.Article.load( 4 ),
		]).then( ( values ) => {
			return values;
		});
	}

	function setHeader() {
		ctx.title = 'Willkommen im RG-Zuchtbuch';
		ctx.submenu = [];
		ctx.crumbs = [
			{name: '.'}
		];
	}



</script>

<section in:slide={{duration:cfg.fadeIn}}>
	{#if articles}
		<Home {articles} />
	{/if}
</section>

