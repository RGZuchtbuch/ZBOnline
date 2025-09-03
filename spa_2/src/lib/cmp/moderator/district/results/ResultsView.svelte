<script>
	import { fade, slide } from 'svelte/transition';
	import { cfg } from '$lib/js/store.svelte.js';
	import { dec, fullName, shortName, txt } from '$lib/js/tools.js';

	let { district, year, results } = $props();


</script>

{#if district && year && results && results.sections}
	{#if results.sections.length === 0}
		<div class='text-center'>Leider noch keine Eingaben</div>
	{:else}
		<div class='flex flex-col' in:fade>
			{#each results.sections as section}
				<div class='flex flex-row section items-end sticky top-14'>
					<span class='grow pl-2'>{section.name}</span>
					<span class='flex flex-col'>
						<span class='flex flex-row text-xs text-center'>
							<span class='w-24'></span>
							<span class='w-2 text-gray-400'>|</span>

							{#if section.id === 5}
								<span class='w-24'>-</span>
							{:else}
								<span class='w-24'>Legen</span>
							{/if}

							<span class='w-2 text-gray-400'>|</span>

							<span class='w-48'>Bruten</span>

							<span class='w-2 text-gray-400'>|</span>

							<span class='w-24'>Schau</span>

							<span class='w-2 text-gray-400'>|</span>
						</span>
						<span class='flex flex-row text-xs text-center'>
							<span class='w-12'>Zuchten</span>
							{#if section.id === 5}
								<span class='w-12'>Paare</span>
							{:else}
								<span class='w-12'>Stämme</span>
							{/if}

							<span class='w-2 text-gray-400'>|</span>

							{#if section.id === 5}
								<span class='w-12'></span>
								<span class='w-12'></span>
							{:else}
								<span class='w-12'>Eier</span>
								<span class='w-12'>Gewicht</span>
							{/if}

							<span class='w-2 text-gray-400'>|</span>

							{#if section.id === 5}
								<span class='w-12'>Gelegt</span>
								<span class='w-12'>-</span>
								<span class='w-12'>Küken</span>
								<span class='w-12'>Kü/Pa</span>
							{:else}
								<span class='w-12'>Eingel.</span>
								<span class='w-12'>Befr.</span>
								<span class='w-12'>Geschl.</span>
								<span class='w-12'>-</span>
							{/if}

							<span class='w-2 text-gray-400'>|</span>

							<span class='w-12'>Tiere</span>
							<span class='w-12'>Pkt</span>

							<span class='w-2 text-gray-400'>|</span>

							<span class='w-8' title='Züchter wenn als Stamm eingegeben'>Zücht</span>
						</span>
					</span>
				</div>


				{#each section.breeds as breed}
					<div class='flex flex-row pl-4' class:accepted={breed.result && breed.result.pairId && breed.result.accepted} class:notaccepted={breed.result && breed.result.pairId && !breed.result.accepted}>
						<span class='w-4'></span>
						<span class='grow'>
							{breed.name}
							{#if breed.result}
								<sup class='' title={`Gruppe ${breed.result.group}`}> {breed.result.group} </sup>
							{/if}
						</span>
						{#if breed.result}

							{@render result( section, breed.result )}
						{/if}
					</div>


					{#each breed.colors as color}
						<div class='flex flex-row pl-10' class:accepted={color.result.pairId && color.result.accepted} class:notaccepted={color.result.pairId && !color.result.accepted}>
							<span class='w-4'></span>
							<span class='grow italic'>
								{color.name}
								<sup class='' title={`Gruppe ${color.result.group}`}> {color.result.group} </sup>
							</span>
							{@render result( section, color.result )}
						</div>
					{/each}
				{/each}
			{/each}

		</div>

	{/if}
{/if}




{#snippet result( section, result )}

	<span class='w-12 number'>{ dec( result.breeders ) }</span>
	<span class='w-12 number'>{ dec( result.pairs ) }</span>

	<span class='w-2'></span>

	<span class='w-12 number'>{ dec( result.lay.eggs, 0 ) }</span>
	<span class='w-12 number'>{ dec( result.lay.weight, 1 ) }</span>

	<span class='w-2'></span>

	{#if section.id === cfg.pigeons}
		<span class='w-12 number'>{ dec( result.brood.eggs ) }</span>
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
		<a class='w-8 text'
		   href={`/moderator/${result.districtId}/breeder/${result.breeder.id}/pair/${result.pairId}`}
		   title={`Zur Stamm von Züchter ${fullName(result.breeder)}`}
		>
			{ shortName( result.breeder ) }
		</a>

	{:else}
		<span class='w-8'></span>
	{/if}
{/snippet}


<style>
	h3 {
		@apply text-center text-xl border-header bg-header text-header font-bold sticky top-1;
	}
	p.info {
		@apply px-8 py-4 text-center;
	}
	.section {
		@apply mt-4 py-1 font-bold border-header bg-teal-200 text-black; /* somehow the app.css classes are overridden. */
	}
    .number {
        @apply px-1 text-right;
    }
    .text {
        @apply px-1 text-center;
    }

	.accepted {
		@apply bg-teal-50;
	}
    .notaccepted {
        @apply bg-orange-50;
    }

	span {
		@apply align-bottom;
	}
</style>