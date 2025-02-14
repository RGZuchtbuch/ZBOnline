<script>
	import { app } from '$lib/js/store.svelte.js';
	import Articles from '$lib/cmp/article/Articles.svelte';

	let { data } = $props();

	app.articles = data.articles;

	app.title = 'Info zum BDRG Zuchtbuch';
	app.menu.trail = [
		{ name:'Start',  href:'/' },
		{ name:'Info',   href:'/article' },
	];
	app.menu.options = [];
</script>

<Articles articles={data.articles} />
