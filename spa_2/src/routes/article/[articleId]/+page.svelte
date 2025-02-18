<script>
	import { page } from '$app/state';
	import { fade, fly, slide } from 'svelte/transition';
	import { app } from '$lib/js/store.svelte.js';
	import api from '$lib/js/api.js';
	import Article from '$lib/cmp/article/Article.svelte';

	let {data } = $props();

	app.title = null; // to set after loading
	app.menu.trail = [
		{name: 'Home', href: '/'},
		{name: 'Info', href: '/article'}
	];
	app.menu.options = [
	];

</script>

<Article article={data.article} />
