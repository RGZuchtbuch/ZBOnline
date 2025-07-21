<script>
	import {setContext} from 'svelte';
	import { page } from '$app/state';
	import { ctx, store } from '$lib/js/store.svelte.js';
	import Article from '$lib/cmp/article/Article.svelte';

	let { data } = $props();

	let article = $state( data.article );
	//setContext( 'article', article );
	//ctx.article = data.article
	$effect( async () => {
		//ctx.article = data.article
		//article = data.article;
	})

 	$effect( async () => {
		ctx.header = {
			title : article.title ? article.title : '?',
			menu : {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Beiträge', href: '/article'},
					{name: article.title },
				],
				options: [
					{name: 'Verbände', href: '/federation'},
					{name: 'Standard', href: '/standard'},
					{name: 'Leistungen', href: '/report'},
				],
			},
		};
 	});


</script>

{#if data.article}
	<Article {article} />
{/if}