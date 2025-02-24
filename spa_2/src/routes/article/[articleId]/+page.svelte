<script>
	import { getContext } from 'svelte';
	import Article from '$lib/cmp/article/Article.svelte';

	let page = getContext( 'page' );
	let article = getContext( 'article' );

	page.title = article.title; // to set after loading
	page.menu = {
		trail: [
			{ name: 'Home', href: '/' },
			{ name: 'Info', href: '/article' },
			{ name: article.title, href: `/article/${article.id}` },
		],
		options: [],
	};

</script>

<Article article={article} />
