<script>
	import { slide } from 'svelte/transition';
	import { page } from '$app/state';
	import Section from './Section.svelte';

	let { standard } = $props();
</script>

Admin:
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
		@apply m-4 border;
	}

	h3 {
        @apply w-full bg-teal-200 rounded-t text-center
	}

</style>
