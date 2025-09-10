<script>
	import { slide } from 'svelte/transition';
	import {ctx} from '$lib/js/store.svelte.js';

	let { color } = $props();
	let authorized = $state( ctx.user && ctx.user.admin );
</script>

{#if color}
	<li class='italic' title='Farbenschlag'>• {color.name}</li>
{/if}

<style>
    li {
        @apply flex flex-row p-2 italic;
    }
</style>