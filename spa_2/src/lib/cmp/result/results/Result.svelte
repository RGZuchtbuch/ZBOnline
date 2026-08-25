<script>
	import { page } from '$app/state';
	import {dec, fullName, shortName} from '$lib/js/tools.js';
	import {cfg} from '$lib/js/store.svelte.js';

	let { result, section } = $props();

</script>


<section class='flex flex-row'>

	<span class='w-16 text-center text-sm'>{ result.pairId ? 'Stamm' : result.breedingId ? 'Zucht' : 'Verband' }</span>
	<span class='w-12 number'>{ dec( result.breeders ) }</span>
	<span class='w-12 number'>{ dec( result.pairs ) }</span>

	<span class='w-2 text-gray-200'>|</span>
	
	<span class='w-12 number'>{ dec( result.lay.eggs, 0 ) }</span>
	<span class='w-12 number'>{ dec( result.lay.weight, 1 ) }</span>

	<span class='w-2 text-gray-200'>|</span>

	{#if section.id === cfg.pigeons}
		<span class='w-12 number'>{ dec( result.brood.eggs/2 ) }</span>
		<span class='w-12'></span>
		<span class='w-12 number'>{ dec( result.brood.hatched ) }</span>
		<span class='w-12 number'>{ dec( result.pairs?result.brood.hatched/result.pairs:null, 1 )}</span>
	{:else}
		<span class='w-12 number'>{ result.brood.eggs}</span>
		<span class='w-12 number'>{ result.brood.fertile}</span>
		<span class='w-12 number'>{ result.brood.hatched}</span>
		<span class='w-12'></span>
	{/if}

	<span class='w-2 text-gray-200'>|</span>

	<span class='w-12 number'>{ result.show.count}</span>
	<span class='w-12 number'>{ dec( result.show.score, 1 ) }</span>

	<span class='w-2 text-gray-200'>|</span>

	{#if result.pairId }
		<span class='flex flex-row w-16 text-center'>

			<span class='grow' title={`Zur Stamm von Züchter ${fullName(result.breeder)}`}>
				{ shortName( result.breeder ) }
			</span>
			<span class='w-4 text-red-600' class:accepted={result.accepted}>{result.accepted ? '✓' : '✗'}</span>
		</span>
	{:else if result.breedingId }
		<span class='flex flex-row w-16 text-center'>
			<span class='grow' title={`Zur Stamm von Züchter ${fullName(result.breeder)}`}>
				{ shortName( result.breeder ) }
			</span>
			<span class='w-4 text-green-600'> </span>
		</span>
	{:else}
		<span class='flex flex-row w-16'>
			<span class='grow'></span>
			<span class='w-4'></span>
		</span>
	{/if}
</section>


<style>
	.accepted {
		@apply text-green-600;
	}
	.number {
		@apply text-right pr-1;
	}
</style>