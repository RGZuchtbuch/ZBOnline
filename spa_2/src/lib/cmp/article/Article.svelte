<script>
	import { page } from '$app/state';
	import { fade, fly, slide } from 'svelte/transition';
	import { app } from '$lib/js/store.svelte.js';
	import api from '$lib/js/api.js';

	//let article = $state( null );
	app.title = null;

	let { article } = $props();
	// api.article.get( page.params.articleId ).then( ( response ) => {
	// 	article = response.article;
	// 	app.title = article.title;
	// 	app.menu.trail.push( {name: article.title.substring(0, 8)+'..', href:null} );
	// } );

	$inspect( 'Article', article );
</script>

{#if article}
	<div class='flex flex-col' in:slide={{duration:400}} >
		<p class='text-xs text-center italic'>
			{article.modified}
		</p>
		<p>
			{@html article.html}
		</p>
	</div>
{/if}
