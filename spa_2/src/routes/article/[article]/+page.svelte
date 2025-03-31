<script>
	//import store from '$lib/js/store.svelte.js';
	import { Article } from '$lib/js/article.svelte.js'
	import ArticleCmp from '$lib/cmp/article/Article.svelte';
	import { page } from '$app/state';

	import store from '$lib/js/store.svelte.js'

	//let { data } = $props();

	let article = $state( null );

 	$effect( async () => {
		article = await Article.load( page.params.article );
 		setHeader( page.url );
 	})
//
	function setHeader( url ) {
		if( article ) {
			const title = article.title; // to set after loading
			const menu =  {
				trail: [
					{name: 'Home', href: '/'},
					{name: 'Info', href: '/article'},
					{name: 'Beitrag', href: url.href},
				],
				options: [],
			}
			store.title = title;
			store.menu  = menu;
		}
	}


</script>

{#if article}
	<ArticleCmp {article} />
{/if}