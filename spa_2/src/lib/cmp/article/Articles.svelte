<script>
	import { fade, fly, slide } from 'svelte/transition';
	import {goto} from '$app/navigation';
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';

	let { articles } = $props();

	let canEdit = $derived( ctx.user && ctx.user.admin )

</script>

{#if articles}
	<!--h2 class='header'>Alle Artikel zum Zuchtbuch</h2-->
	{#if canEdit}
		<div class='flex flex-row justify-end p-1'>
			<a href='/article/0'>[+]</a>
		</div>
	{/if}

	<ol in:slide>

		{#each articles as article, i}
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
{:else}
	Keine Beiträge gefunden
{/if}


<style>
	li a {
		@apply flex flex-row border-b p-2 gap-x-2;
	}
	ol {
		@apply px-6 py-4;
	}
</style>
