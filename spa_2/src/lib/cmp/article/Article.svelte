<script>
	import { fade, fly, slide } from 'svelte/transition';

	let { article } = $props();

</script>

{#if article}
	<div class='flex flex-col' in:slide={{duration:400}} >
		<p class='text-xs text-center italic'>
			{article.modified}
		</p>
		<p>
			{@html article.html}
		</p>
	</div>
{/if}
