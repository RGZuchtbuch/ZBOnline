<script>
	import { page } from '$app/state';
	import { dec } from '$lib/js/toolbox.js';
	let { results } = $props();

	let last = {
		year:0, rootSection:0, breed:0
	}
</script>

<h3>Eingegebene Leistungen</h3>
<p class='info'>
	Leistungen können als gesamt Leistung für einemn Verband eingegeben werden, oder als einzelne Meldungen der Züchter.
</p>

<div class='flex flex-col'>
	{#each results.years as year, i}
		<div class='year'>{year.year}</div>
		{#each year.sections as section}
			<div class='w-full pl-4'>{section.name}</div>
			{#each section.breeds as breed}
				{#if breed.result}
					<div class='flex flex-row pl-8'>
						<div class='w-96'>{breed.name}</div>
						<div class='w-16'>{breed.result.lay.eggs}</div>
						<div class='w-16'>{breed.result.lay.weight}</div>
						<div class='w-4'></div>
						<div class='w-12'>e{breed.result.brood.eggs}</div>
						<div class='w-12'>{breed.result.brood.fertile}</div>
						<div class='w-12'>{breed.result.brood.hatched}</div>
						<div class='w-4'></div>
						<div class='w-12'>{breed.result.show.count}</div>
						<div class='w-16'>{ dec( breed.result.show.score, 1) }</div>
						{#if breed.result.breeder.id }
							<div class='w-8'>{breed.result.breeder.id}</div>
							<div class='w-24'>{breed.result.breeder.lastname.substring(0,1)}.{breed.result.breeder.firstname.substring(0,1)}</div>
						{/if}
					</div>
				{:else}
					<div class='w-full pl-8'>b {breed.name}</div>
				{/if}
				{#each breed.colors as color}
					<div class='flex flex-row pl-8'>
						<span class='w-96 pl-8'>{color.name}</span>
						<span class='w-16'>{ dec( color.result.lay.eggs, 1 ) }</span>
						<span class='w-16'>{ dec( color.result.lay.weight, 1 ) }</span>
						<span class='w-4'></span>
						<span class='w-12'>e{color.result.brood.eggs}</span>
						<span class='w-12'>{color.result.brood.fertile}</span>
						<span class='w-12'>{color.result.brood.hatched}</span>
						<span class='w-4'></span>
						<span class='w-12'>{color.result.show.count}</span>
						<span class='w-16'>{ dec( color.result.show.score, 1 ) }</span>
						{#if color.result.breeder.id }
							<span class='w-8'>{color.result.breeder.id}</span>
							<span class='w-24'>{color.result.breeder.firstname.substring(0,1)}.{color.result.breeder.lastname.substring(0,1)}</span>
						{/if}
					</div>
				{/each}
			{/each}
		{/each}
	{/each}
</div>

<style>
	h3 {
		@apply text-center text-xl font-bold;
	}
	p.info {
		@apply m-4;
	}
	.year {
		@apply w-full text-center text-xl font-bold border-y bg-lime-200;
	}
</style>