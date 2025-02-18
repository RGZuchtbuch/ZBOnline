<script>
	import { page } from '$app/state';
	import { dec } from '$lib/js/toolbox.js';
	let { results } = $props();


	console.log( 'Br', results);
</script>


<h3 class=''>Eingegebene Leistungen</h3>
<p class='info'>
	Leistungen können als gesamt Leistung für einemn Verband eingegeben werden, oder als einzelne Meldungen beim Züchter.<br>
	Hier eine Liste von alle Eingaben.
</p>

<div class='flex flex-col'>
	{#each results.sections as section}
		<div class='flex flex-row section items-end'>
			<span class='grow pl-2'>{section.name}</span>
			<span class='flex flex-col'>
				<span class='flex flex-row text-xs text-center'>
					<span class='w-2'></span>
					<span class='w-20'></span>
					<span class='w-4'></span>
					<span class='w-24'>Legen</span>
					<span class='w-4'></span>
					<span class='w-28'>Bruten</span>
					<span class='w-4'></span>
					<span class='w-20'>Schau</span>
				</span>
				<span class='flex flex-row text-xs text-center'>
					<span class='w-12'>Zuchten</span>
					<span class='w-12'>Stämme</span>
					<span class='w-2'></span>
					<span class='w-12'>Eier</span>
					<span class='w-14'>Gewicht</span>
					<span class='w-2'></span>
					<span class='w-10'>Eingel.</span>
					<span class='w-10'>Befr.</span>
					<span class='w-10'>Geschl.</span>
					<span class='w-2'></span>
					<span class='w-10'>Tiere</span>
					<span class='w-12'>P.</span>
					<span class='w-10'>Meld.</span>
				</span>
			</span>
		</div>
		{#each section.breeds as breed}
			<div class='flex flex-row pl-4'>
				<span class='grow'>{breed.name}</span>
				{#if breed.result}
					{@render result( breed.result )}
				{/if}
			</div>
			{#each breed.colors as color}
				<div class='flex flex-row pl-8'>
					<span class='grow italic'>{color.name}</span>
					{@render result( color.result )}
				</div>
			{/each}
		{/each}
	{/each}
</div>

{#snippet result( result )}
	<span class='w-12 number'>{ dec( result.breeders ) }</span>
	<span class='w-12 number'>{ dec( result.pairs ) }</span>
	<span class='w-2'></span>
	<span class='w-12 number'>{ dec( result.lay.eggs, 0 ) }</span>
	<span class='w-14 number'>{ dec( result.lay.weight, 1 ) }</span>
	<span class='w-2'></span>
	<span class='w-10 number'>{ result.brood.eggs}</span>
	<span class='w-10 number'>{ result.brood.fertile}</span>
	<span class='w-10 number'>{ result.brood.hatched}</span>
	<span class='w-2'></span>
	<span class='w-10 number'>{ result.show.count}</span>
	<span class='w-12 number'>{ dec( result.show.score, 1 ) }</span>
	{#if result.breeder }
		<span class='w-10 text'>{ result.breeder.firstName.substring(0,1)}.{ result.breeder.lastName.substring(0,1)}</span>
	{:else}
		<span class='w-10'></span>
	{/if}
{/snippet}


<style>
	h3 {
		@apply text-center text-xl font-bold;
	}
	p.info {
		@apply px-8 py-4 text-center;
	}
	.section {
		@apply mt-4 py-1 font-bold bg-teal-200;
	}
    .number {
        @apply px-1 text-right;
    }
    .text {
        @apply px-1 text-center;
    }
	.year {
		@apply w-full text-center text-xl font-bold border-y bg-lime-200;
	}

	span {
		@apply align-bottom;
	}
</style>