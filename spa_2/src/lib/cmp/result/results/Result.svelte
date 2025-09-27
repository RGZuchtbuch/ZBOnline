<script>
	import { page } from '$app/state';
	import {dec, fullName, shortName} from '$lib/js/tools.js';
	import {cfg} from '$lib/js/store.svelte.js';

	let { result, section } = $props();

</script>


<section class='flex flex-row'>

	<span class='w-12 number'>{ dec( result.breeders ) }</span>
	<span class='w-12 number'>{ dec( result.pairs ) }</span>

	<span class='w-2'></span>

	<span class='w-12 number'>{ dec( result.lay.eggs, 0 ) }</span>
	<span class='w-12 number'>{ dec( result.lay.weight, 1 ) }</span>

	<span class='w-2'></span>

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

	<span class='w-2'></span>

	<span class='w-12 number'>{ result.show.count}</span>
	<span class='w-12 number'>{ dec( result.show.score, 1 ) }</span>

	<span class='w-2'></span>

	{#if result.breeder }
		<span class='w-16 flax flex-row text-center'>
			<a class=''
			   href={`${page.url.pathname}/breeder/${result.breeder.id}/pair/${result.pairId}`}
			   title={`Zur Stamm von Züchter ${fullName(result.breeder)}`}
			>
				{ shortName( result.breeder ) }
			</a>
			<span class='text-red-600' class:accepted={result.accepted}>{result.accepted ? '✓' : '✗'}</span>
		</span>
	{:else}
		<span class='w-16'></span>
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