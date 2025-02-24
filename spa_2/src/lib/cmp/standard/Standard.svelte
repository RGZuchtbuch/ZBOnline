<script>
	import { slide } from 'svelte/transition';
	import Section from '$lib/cmp/standard/Section.svelte';

	let { standard } = $props();
</script>

<h3>
	Standard, Sparten, Gruppen, Rassen und Farbenschläge
</h3>

{#if standard }
	<div in:slide>
		<Section section={ standard.root } unfold={true}/>
	</div>
{/if}

<style>


</style>