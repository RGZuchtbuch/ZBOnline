<script>
	import { slide } from 'svelte/transition';
	import { page } from '$app/state';
	import Section from '$lib/cmp/standard/Section.svelte';

	let { standard } = $props();
</script>

<section class='pl-6'>
	<header class='p-2 border-header bg-header text-header text-center'>Standard, Sparten, Rassen und Farbenschläge</header>

	<p class=' mt-2 text-center print:hidden'>
		Dieser Auflistung der Sparten, Rasse und Farbenschläge wird noch ergänzt.<br>
		Wenn deine Rasse, oder Daten dar zu, Fehlt, bitte benachrichtige dein Obmann.
	</p>

	{#if standard }
		<Section section={ standard.root } unfold={true}/>
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
