<script>
	import { slide } from 'svelte/transition';

	let { color } = $props();
</script>

{#if color}
	<li class='' title='Farbenschlag'><div class='grow italic'>• {color.name}</div></li>
{/if}

<style>
    li {
        @apply flex flex-row italic;
    }
</style>