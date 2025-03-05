<script>
	import { slide } from 'svelte/transition';

	let { color } = $props();
</script>

{#if color}
	<li class='row'><div class='grow italic'>{color.name}</div></li>
{/if}

<style>
    div.row {
        @apply flex flex-row italic;
    }
</style>