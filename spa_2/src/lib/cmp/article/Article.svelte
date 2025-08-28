<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { ctx } from '$lib/js/store.svelte.js';

	let { article } = $props();

</script>

{#if article}
	<section>
		<div class='flex flex-row items-center justify-end gap-x-2 p-2 text-xs italic'>
			{article.author}, {article.modified}
		</div>

		<p class='px-6 py-2'>
			{@html article.html}
		</p>
	</section>

{/if}

<style>
	.meta {
		font-size: 0.6em;
		@apply italic;
	}
</style>
