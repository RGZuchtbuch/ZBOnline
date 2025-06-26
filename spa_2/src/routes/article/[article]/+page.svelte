<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import ArticleCmp from '$lib/cmp/article/Article.svelte';

 	$effect( async () => {
 		setHeader( page.url );
 	})
//
	function setHeader( url ) {
		if( ctx.article ) {
			ctx.header.title = ctx.article.title; // to set after loading
			ctx.header.menu  = {
				trail: [
					{name: 'Home', href: '/'},
					{name: 'Info', href: '/article'},
					{name: 'Beitrag', href: url.href},
				],
				options: [],
			}
		}
	}


</script>

{#if ctx.article}
	<ArticleCmp article={ctx.article} />
{/if}