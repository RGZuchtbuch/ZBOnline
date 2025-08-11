<script>
	import {onMount} from 'svelte';
	import { page } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';
	import Article from '$lib/cmp/article/Article.svelte';
	import {addCrumb} from '$lib/js/tools.js';

//	addCrumb( { name:'?', url:page.url } );

	$effect( async () => {
		if ( dirty.article || page.url ) loadArticle(+page.params.article);
	});
	$effect( () => {
		if( ctx.article ) {
			addCrumb( { name:ctx.article.title.substring( 0, 8 ), url:page.url } );
			setHeader();
		}
	})

	async function loadArticle( id ) {
		console.log("load Article")
		dirty.article = false;
		ctx.article = null;
		ctx.article = await model.Article.load( id );
	}

	function setHeader() {
		//ctx.menustate[ '/article' ] = page.url.href;
		ctx.title = ctx.article.title ? ctx.article.title : '?';
		ctx.submenu = [];
		ctx.crumbs = [
			{name: 'Start', href: '/'},
			{name: 'Infos', href: '/article'},
			{name: ctx.article.title.substring( 0, 20 )+'..' },
		];
		// ctx.header = {
		// 	title : ctx.article.title ? ctx.article.title : '?',
		// 	menu : {
		// 		trail: [
		// 			{name: 'Start', href: '/'},
		// 			{name: 'Beiträge', href: '/article'},
		// 			{name: ctx.article.title },
		// 		],
		// 		options: [
		// 			{name: 'Verbände', href: '/federation'},
		// 			{name: 'Standard', href: '/standard'},
		// 			{name: 'Leistungen', href: '/report'},
		// 		],
		// 	},
		// };
	}

</script>

{#if ctx.article}
	<Article article={ctx.article} />
{/if}