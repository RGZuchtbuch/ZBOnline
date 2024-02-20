<script>

	import {createEventDispatcher} from 'svelte';

	export let open = false;
	export let enabled = false; // enable if there are children

	let className = '';
	export { className as class };

	const dispatch = createEventDispatcher();

	function onClick( event ) {
        if( enabled ) {
            open = !open;
            dispatch('toggle', open);
        }
	}
// ▲▼▶▽○ ◦   https://www.compart.com/en/unicode/block/U+25A0
</script>

<button type='button' class={'w-6 text-left cursor-pointer '+className} on:click={onClick} >
	{#if enabled}
		{#if open}▽{:else}▷{/if}
	{:else}
		◦
	{/if}
</button>

<style>

</style>