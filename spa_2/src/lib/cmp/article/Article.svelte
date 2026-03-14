<script>
	import { fade, fly, slide } from 'svelte/transition';
	import { ctx } from '$lib/js/store.svelte.js';

	let { article } = $props();

</script>

<section class=''>
	{#if article}
		<div class='flex flex-row items-center justify-end gap-x-2 p-2 text-3xs italic'>
			{article.author}, {article.modified}
		</div>

		<div class='px-2 md:px-16'>
			{@html article.html}
		</div>
	{/if}
</section>


<style>
</style>
