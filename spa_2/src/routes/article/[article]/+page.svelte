<script>
	import { ctx } from '$lib/js/store.svelte.js';
	import Article from '$lib/cmp/article/Article.svelte';

	let { data } = $props();

	ctx.article = $state( data.article );
	$effect( async () => {
		ctx.article = data.article;
	})

 	$effect( async () => {
		ctx.header = {
			title : data.article.title ? data.article.title : '?',
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

{#if ctx.article}
	<Article article={ctx.article} />
{/if}