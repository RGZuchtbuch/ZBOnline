<script>
	import { slide } from 'svelte/transition';
	import { page } from '$app/state';
	import store, { standard } from '$lib/js/store.svelte.js';
	import Section from '$lib/cmp/standard/Section.svelte';


</script>

<section>
	<h3>Standard, Sparten, Gruppen, Rassen und Farbenschläge</h3>

	{#if page.data.standard }
		<ul in:slide>
			<Section section={ page.data.standard.root } unfold={true}/>
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
