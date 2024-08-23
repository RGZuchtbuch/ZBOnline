<script>
	import {getContext} from 'svelte';
	import {getProduction} from '../../js/util.js';
	import { meta, router } from 'tinro';
	import { slide } from 'svelte/transition';
	import Link from './Link.svelte';

	export let name = '?';
	export let focus = null;
	export let url = null;
	export let href = null;

	let selected = false;

	const route = meta();

	function getFocus() {
		focus = focus === name ? null : name;
	}

	function onChange( router ) {
		selected = router.path.startsWith( url );
		//console.log( 'onChange', router.path, url, selected );
	}

	$: onChange( $router );
</script>


<div class='flex flex-col'>
	{#if href}
		<a class='w-full md:w-auto mx-1 border-0 rounded-none text-center text-white' class:selected {href}> {name} </a >
	{:else}
		<button type='button' class='w-full md:w-auto border-0 rounded-none text-center text-white' class:selected on:click={getFocus}>{name}</button>

		{#if ! href && focus === name }
			<div class='ml-2'>
				<div class='relative md:absolute z-50 border border-gray-400 rounded-b flex flex-col bg-gray-100 p-2 justify-center' transition:slide>
					<slot></slot>
				</div>
			</div>
		{/if}
	{/if}

</div>

<style>

	.selected {
		@apply font-bold;
	}
</style>