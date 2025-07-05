<script>
	import { page } from '$app/state';
	import { ctx, store } from '$lib/js/store.svelte.js';
	import Article from '$lib/cmp/article/Article.svelte';

	let { data } = $props();

	$effect( async () => {
		ctx.article = data.article
	})
	//ctx.article = $derived( data.article );

 	$effect( async () => {
		ctx.header = {
			title : ctx.article.title,
			menu : {
				trail: [
					{name: 'Start', href: '/'},
					{name: 'Beiträge', href: '/article'},
					{name: ctx.article.title, href: null},
				],
				options: [
					{name: 'Beiträge', href: '/article'},
					{name: 'Verbände', href: '/federation'},
					{name: 'Standard', href: '/standard'},
					{name: 'Leistungen', href: '/report'},
				],
			},
		};
 	});


</script>

{#if ctx.article}
	<Article article={ctx.article} />
{/if}