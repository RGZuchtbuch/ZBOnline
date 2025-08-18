<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { page } from '$app/state';

	import { ctx } from '$lib/js/store.svelte.js';
	import District from '$lib/cmp/district/districts/District.svelte';

	let { articles } = $props();

	let canEdit = $derived( ctx.user && ctx.user.admin )

</script>

<section>
	{#if articles}
		<!--h2 class='header'>Alle Artikel zum Zuchtbuch</h2-->

		{#if canEdit}
			<div class='flex flex-row justify-end'>
				<a href='/admin/article/0' title='Neuer Beitrag'>[+]</a>
			</div>
		{/if}

		<header class='flex flex-row px-2 gap-x-2 border-header bg-header text-header'>
			<div class='w-12'>Level</div>
			<div class='grow'>Titel</div>
			<div class='w-32'>Von</div>
		</header>

		<ol in:slide>

			{#each articles as article, i}
				<li class=''>
					<a class='grow flex flex-row gap-x-2 py-2' href={`${page.url.pathname}/${article.id}`}>
						<div class='w-12 text-right '> {article.level}</div>
						<div class='grow'>{article.title}</div>
						<div class='w-32'>{article.author}</div>
					</a>
				</li>
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
	hewader {
        @apply flex flex-row border-b px-8 py-2 gap-x-2 sticky top-0 text-left;
	}
    ol {
        @apply px-2 py-4;
    }


</style>

