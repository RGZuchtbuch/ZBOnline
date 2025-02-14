<script>
	import { page } from '$app/state';
	import { app } from '$lib/js/store.svelte.js';

	import api from '$lib/js/api.js';
	import { email_icon, link_icon } from '$lib/cmp/snippets.svelte';

	let { color } = $props();

</script>

{#if color}
	<div class='row'><div class='grow'>{color.name}</div></div>
{/if}

<style>
    div.row {
        @apply flex flex-row border border-y-amber-400;
    }
</style>