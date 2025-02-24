<script>
	import { slide } from 'svelte/transition';

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