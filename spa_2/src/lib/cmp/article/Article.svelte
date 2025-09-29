<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { ctx } from '$lib/js/store.svelte.js';

	let { article } = $props();

</script>

<section class='px-1 md:px-16'>
	{#if article}
		<div class='flex flex-row items-center justify-end gap-x-2 p-2 text-xs italic'>
			{article.author}, {article.modified}
		</div>

		<p class=''>
			{@html article.html}
		</p>
	{/if}
</section>


<style>
</style>
