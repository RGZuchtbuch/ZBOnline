<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { ctx } from '$lib/js/store.svelte.js';

	let { article } = $props();

</script>

{#if article}
	<section class='px-16'>
		<div class='flex flex-row items-center justify-end gap-x-2 p-2 text-xs italic'>
			{article.author}, {article.modified}
		</div>

		<p class=''>
			{@html article.html}
		</p>
	</section>

{/if}

<style>
</style>
