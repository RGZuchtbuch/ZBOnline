<script>
	import { app } from '$lib/js/store.svelte.js';

	let { district } = $props();

</script>

{#if district }
	<div>District {district.name}</div>
	<div>Room for quick links</div>

	<div>Verbands daten</div>

	<div>Stämme</div>

	<div>Leistungen</div>

{/if}