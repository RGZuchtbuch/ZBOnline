<script>
	import store, { user } from '$lib/js/store.svelte.js';
	import Article from '$lib/cmp/article/Article.svelte';
	import { page } from '$app/state';

	let { data } = $props();

	$effect( () => {
		setHeader( page );
	})




	function setHeader( page ) {
		console.log('Article.seth', data.article.title);
		if( data.article ) {
			const title = data.article.title; // to set after loading
			const menu =  {
				trail: [
					{name: 'Home', href: '/'},
					{name: 'Info', href: '/article'},
					{name: 'Beitrag', href: page.url.href},
				],
				options: [],
			}
			store.title.update( () => title );
			store.menu.update(() => menu);
		}
	}


</script>

<Article bind:article={data.article}/>
