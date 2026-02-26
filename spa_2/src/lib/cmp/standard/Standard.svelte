<script>
	import { slide } from 'svelte/transition';
	import { page } from '$app/state';
	import Section from '$lib/cmp/standard/Section.svelte';

	let { standard } = $props();
</script>

<section class='pl-6'>
	{#if standard }
		<Section section={ standard.root } unfold={true}/>
	{/if}
	<hr>
	<p class=' mt-2 text-center print:hidden'>
		Wenn deine Rasse, oder Daten dazu, fehlt, dann benachrichtige deinen Obmann.
	</p>
</section>

<style>
	section {
		@apply border;
	}

	h3 {
        @apply w-full bg-teal-200 rounded-t text-center
	}

</style>
