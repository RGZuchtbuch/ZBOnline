<script>
	import { slide } from 'svelte/transition';
	import { page } from '$app/state';
	import Section from '$lib/cmp/standard/Section.svelte';

	let { standard } = $props();
</script>

<section>
	<header class='p-2 border-header bg-header text-header text-center'>Standard, Sparten, Rassen und Farbenschläge</header>

	{#if standard }
		<ul in:slide>
			<Section section={ standard.root } unfold={true}/>
		</ul>
	{/if}
</section>

<style>
	section {
		@apply border;
	}

	h3 {
        @apply w-full bg-teal-200 rounded-t text-center
	}

</style>
