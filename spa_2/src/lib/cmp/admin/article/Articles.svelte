<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { page } from '$app/state';

	import { ctx } from '$lib/js/store.svelte.js';
	import District from '$lib/cmp/district/districts/District.svelte';

	let { articles } = $props();

	let canEdit = $derived( ctx.user && ctx.user.admin )

</script>

<section>
	{#if canEdit}
		<div class='flex flex-row justify-end p-2'>
			<a class='border-button bg-button text-button py-0 px-2' href='/admin/article/0' title='Neuer Beitrag'>+</a>
		</div>
	{/if}

	{#if articles}

		<header class='flex flex-row px-2 gap-x-2 border-header bg-header text-header'>
			<span class='w-8 text-right'>#</span>
			<span class='w-12 text-right'>Folge</span>
			<span class='grow'>Titel</span>
			<span class='w-48'>Von</span>
			<span class='w-48'>Geändert</span>
		</header>

		{#each articles as article, i}
			<a class='grow flex flex-row gap-x-2 p-2' href={`${page.url.pathname}/${article.id}`}>
				<div class='w-8 text-right '> {i}</div>
				<div class='w-12 text-right '> {article.level}</div>
				<div class='grow'>{article.title}</div>
				<div class='w-48'>{article.author}</div>
				<div class='w-48'>{article.modified}</div>
			</a>
		{/each}
	{:else}
		<h2 class='mt-32 text-center text-xl'>
			Keine Beiträge gefunden
		</h2>
	{/if}
</section>

<style>
	section {
		@apply ml-4;
	}
</style>

