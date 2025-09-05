<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { page } from '$app/state';
	let { articles } = $props();
</script>

<section class='py-4'>
	{#if articles}
		<header class='flex flex-row px-2 gap-x-2 border-header bg-header text-header'>
			<span class='w-4 text-right'>#</span>
			<span class='w-12 text-right'>Folge</span>
			<span class='grow'>Titel</span>
			<span class='w-32'>Von</span>
		</header>

		{#each articles as article, i}
			{#if article.level !== null }
				<a class='grow flex flex-row gap-x-2 p-2' href={`${page.url.pathname}/${article.id}`}>
					<span class='w-4 text-right '>{i+1}</span>
					<span class='w-12 text-right '>{i+1}</span>
					<span class='grow'>{article.title}</span>
					<span class='w-32'>{article.author}</span>
				</a>
			{/if}
		{/each}
	{/if}
	{#if articles && articles.length === 0 }
		Keine Info's gefunden.....
	{/if}
</section>


<style>
	section {
		@apply ml-4;
	}

    ol {
        @apply px-2 py-4;
    }
</style>

