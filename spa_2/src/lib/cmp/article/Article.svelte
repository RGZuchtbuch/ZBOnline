<script>
	import { fade, fly, slide } from 'svelte/transition';

	let { article } = $props();

</script>

{#if article}
	<h2 class='header'>{article.title}</h2>
	<div class='flex flex-col px-8' in:slide={{duration:400}} >
		<div class='meta py-4'>
			{article.author}, {article.modified}
		</div>
		<p>
			{@html article.html}
		</p>
	</div>
{/if}

<style>
	.meta {
		font-size: 0.6em;
		@apply text-right italic;
	}
</style>
