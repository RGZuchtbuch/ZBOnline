<script>
	import { getContext } from 'svelte';

	import store, { article } from '$lib/js/store.svelte.js';
	import Article from '$lib/cmp/article/Article.svelte';

	let { data } = $props();

	let app = getContext( 'state' );

	$effect( () => {
		if( $article ) {
			const title = $article.title;
			const menu = {
				trail: [
					{name: 'Home', href: '/'},
					{name: 'Info', href: '/article'},
					{name: $article.title, href: `/article/${$article.id}`},
				],
				options: [],
			}

			store.title.update(() => title); // to set after loading
			store.menu.update(() => menu);
		}
	});

</script>

<Article/>
