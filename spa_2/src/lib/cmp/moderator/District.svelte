<script>
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';

	let { district } = $props();

</script>

{#if district }
	<h2 class='header'>Verband {district.name}</h2>
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