<script>
	import { slide } from 'svelte/transition';
	import { ctx } from '$lib/js/store.svelte.js';
	import Section from './Section.svelte';

	let { standard } = $props();
</script>

<section>
	<header class='border-header bg-header text-header text-center'>Standard, Sparten, Rassen und Farbenschläge</header>

	{#if standard }
		<Section bind:section={ ctx.standard.root } unfold={true}/>
	{/if}
</section>

<style>
	section {
		@apply border;
	}

</style>
