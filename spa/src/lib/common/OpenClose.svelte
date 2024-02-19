<script>

	import {createEventDispatcher} from 'svelte';

	export let open = false;
	export let enabled = false;

	let className = '';
	export { className as class };

	const dispatch = createEventDispatcher();

	function onClick( event ) {
        if( enabled ) {
            open = !open;
            dispatch('toggle', open);
        }
	}

</script>

<button type='button' class={'w-6 cursor-pointer '+className} on:click={onClick} >
	{#if enabled}
		{#if open}▲{:else}▼{/if}
	{/if}
</button>

<style>

</style>