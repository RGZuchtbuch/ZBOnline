<script>
	import {getContext} from 'svelte';
	import {getProduction} from '../../js/util.js';
	import { slide } from 'svelte/transition';

	export let name = '?';
	export let focus = null;


	function getFocus() {
		focus = focus === name ? null : name;
	}
</script>


<div class='flex flex-col'>
	<button type='button' class='w-full md:w-auto border-0 rounded-none text-center text-white' on:click={getFocus}>{name}</button>
	<div class='ml-2'>
		{#if focus === name }
			<div class='relative md:absolute z-50 border border-gray-400 rounded-b flex flex-col bg-gray-100 p-2 justify-center' transition:slide>
				<slot></slot>
			</div>
		{/if}
	</div>
</div>

<style>
</style>