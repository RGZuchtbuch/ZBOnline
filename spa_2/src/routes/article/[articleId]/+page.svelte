<script>
	import { getContext } from 'svelte';
	import Article from '$lib/cmp/article/Article.svelte';

	let { data } = $props();

	let state = getContext( 'state' );
	//let article = getContext( 'article' );

	$inspect( 'D', data );

	state.title = data.article.title; // to set after loading
	state.menu = {
		trail: [
			{ name: 'Home', href: '/' },
			{ name: 'Info', href: '/article' },
			{ name: data.article.title, href: `/article/${data.article.id}` },
		],
		options: [],
	};

</script>

<Article {...data} />
