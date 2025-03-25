<script>
	import { fade, fly, slide } from 'svelte/transition';
	import {goto} from '$app/navigation';
	import { page } from '$app/state';
	import store, { user } from '$lib/js/store.svelte.js';
	import api from '$lib/js/api.js';

	//let { articles } = $props();

</script>

{#if page.data.articles}
	<!--h2 class='header'>Alle Artikel zum Zuchtbuch</h2-->
	{#if $user && $user.admin}
		<div class='flex flex-row justify-end p-1'>
			<a href='/article/0'>[+]</a>
		</div>
	{/if}
	<ol in:slide>

		{#each page.data.articles as article, i}
			<li class='flex flex-row gap-x-2'>
				<a class='grow' href={`/article/${article.id}`}>
					<div class='text-right '>{i+1}</div>
					<div class='grow'>{article.title}</div>
					<div class='w-32'>{article.author}</div>
					<sup class='w-6'>{article.id}</sup>
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
		@apply m-6;
	}
</style>
