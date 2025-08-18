<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { page } from '$app/state';
	let { articles } = $props();
</script>


<section>
	{#if articles}
		<header class='flex flex-row px-2 gap-x-2 border-header bg-header text-header'>
			<div class='w-4'>#</div>
			<div class='grow'>Beitrag</div>
			<div class='w-32'> Von </div>
		</header>

		<ol in:slide>

			{#each articles as article, i}
				{#if article.level !== null }
					<li class=''>
						<a class='grow flex flex-row gap-x-2 py-2' href={`${page.url.pathname}/${article.id}`}>
							<div class='w-4 text-right '>{i+1}</div>
							<div class='grow'>{article.title}</div>
							<div class='w-32'>{article.author}</div>
						</a>
					</li>
				{/if}
			{/each}
		</ol>

	{:else}
		Keine Beiträge gefunden
	{/if}
</section>


<style>
	section {
		@apply m-0;
	}

    ol {
        @apply px-2 py-4;
    }
</style>

