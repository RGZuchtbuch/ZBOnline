<script>
	import { fade, fly, slide } from 'svelte/transition';
	import store, { articles, user } from '$lib/js/store.svelte.js';
	import api from '$lib/js/api.js';

	async function load() {
		console.log( 'Load articles' );
		const response = await api.article.get();
		if( response && response.articles ) {
			store.articles.update(() => response.articles);
			return true;
		}
		return false;
//	return { articles:response.articles };
	}

	function onAddArticle( event ) {
		articles.update( items => [ ...items, { id:0, title:'Todo', author:null } ] );
	}

	load();

</script>

{#if $articles}
	<h2 class='header'>Alle Artikel zum Zuchtbuch</h2>
	{#if $user && $user.admin}
		<div class='flex flex-row m-1 justify-end'><button type='button' onclick={onAddArticle}>+</button></div>
	{/if}
	<ol in:slide>

		{#each $articles as article, i}
			<li class='flex flex-row gap-x-2'>
				<a class='grow' href={`/article/${article.id}`}>
					<div class='text-right '>{i+1}.{article.id}</div>
					<div class='grow'>{article.title}</div>
					<div class='w-32'>{article.author}</div>
				</a>
			</li>
		{/each}
	</ol>
{/if}
<style>
	a {
		@apply flex flex-row border-b p-2 gap-x-2;
	}
	ol {
		@apply mx-16 my-4;
	}
</style>
