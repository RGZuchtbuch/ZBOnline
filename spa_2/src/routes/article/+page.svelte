<script>
	import { getContext } from 'svelte';

	import Articles from '$lib/cmp/article/Articles.svelte';

	let page     = getContext( 'page' );
	let articles = getContext( 'articles' );

	page.title = 'Info zum BDRG Zuchtbuch';
	page.menu = {
		trail : [
			{ name:'Start',  href:'/' },
			{ name:'Info',   href:'/article' },
		],
		options : [],
	};

</script>

<Articles articles={articles} />
