<script>
	import { fade, fly, slide } from 'svelte/transition';

	import { ctx } from '$lib/js/store.svelte.js';
	import District from '$lib/cmp/district/districts/District.svelte';

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

	<header class='header'>
		<div class='w-4'>#</div>
		<div class='grow'>Beitrag</div>
		<div class='w-32'> Von </div>
	</header>

	<ol in:slide>

		{#each articles as article, i}
			<li class=''>
				<a class='grow' href={`/article/${article.id}`}>
					<div class='w-4 text-right '>{i+1}</div>
					<div class='grow'>{article.title}</div>
					<div class='w-32'>{article.author}</div>
				</a>
			</li>
		{/each}
	</ol>
{:else}
	Keine Beiträge gefunden
{/if}


<style>
	header {
        @apply flex flex-row border-b px-8 py-2 gap-x-2 sticky top-0 text-left;
	}
    ol {
        @apply px-6 py-4;
    }
	a {
		@apply flex flex-row border-b p-4 gap-x-2;
	}

</style>

