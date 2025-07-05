<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';

	let { district } = $props();

</script>

{#if district }
	<p class='content'>
		Intro Tekst
	</p>
	<p>
		Statistiken
	</p>

{/if}


<style>
	p {
		@apply px-6 pt-4;
	}
</style>