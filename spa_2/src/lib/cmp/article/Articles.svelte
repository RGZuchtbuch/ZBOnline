<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { page } from '$app/state';
	import api from '$lib/js/api.js';

	//let articles = $state( [] );
	let {articles} = $props()

	// api.article.get().then( ( response ) => {
	// 	console.log( 'As', response )
	// 	articles = response.articles
	// } );
	$inspect( 'P', articles);
</script>


<p>
	Das Zuchtbuch ...
</p>
<hr>
<h4>Artikel im Zuchtbuch</h4>
<ul in:slide>
	{#each articles as article}
		<li>&#8231; <a href={`/article/${article.id}`}>{article.title}</a></li>
	{/each}
</ul>
