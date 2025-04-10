<script>
	import { dec, txt } from '$lib/js/toolbox.js';

	let { results, district, year } = $props();

	console.log( 'R', results, district, year );

</script>


<div class='flex flex-col'>
	{#if results}
		{#each results.sections as section}
			<div class='flex flex-row section items-end'>
				<span class='grow pl-2'>{section.name}</span>
				<span class='flex flex-col'>
					<span class='flex flex-row text-xs text-center'>
						<span class='w-2'></span>
						<span class='w-20'></span>
						<span class='w-4'></span>
						{#if section.id === 5}
							<span class='w-24'></span>
						{:else}
							<span class='w-24'>Legen</span>
						{/if}
						<span class='w-4'></span>
							<span class='w-28'>Bruten</span>
						<span class='w-4'></span>
						<span class='w-20'>Schau</span>
					</span>
					<span class='flex flex-row text-xs text-center'>
						<span class='w-12'>Zuchten</span>
						<span class='w-12'>Stämme</span>
						<span class='w-2'></span>
						{#if section.id === 5}
							<span class='w-12'></span>
							<span class='w-14'></span>
						{:else}
							<span class='w-12'>Eier</span>
							<span class='w-14'>Gewicht</span>
						{/if}

						<span class='w-2'></span>
						{#if section.id === 5}
							<span class='w-10'>Küken</span>
							<span class='w-10'>Paare</span>
							<span class='w-10'>Kü/Pa</span>
						{:else}
							<span class='w-10'>Eingel.</span>
							<span class='w-10'>Befr.</span>
							<span class='w-10'>Geschl.</span>
						{/if}

						<span class='w-2'></span>
						<span class='w-10'>Tiere</span>
						<span class='w-12'>P.</span>
						<span class='w-10'>Von</span>
					</span>
				</span>
			</div>
			{#each section.breeds as breed}
				<div class='flex flex-row pl-4'>
					<sup class='w-4'>
						{#if breed.result} {breed.result.group} {/if}
					</sup>
					<span class='grow'>{breed.name}</span>
					{#if breed.result}
						{@render result( section, breed.result )}
					{/if}
				</div>
				{#each breed.colors as color}
					<div class='flex flex-row pl-10' class:pair={color.result.pairId !== null}>
						<span class='w-4'></span>
						<span class='grow italic'>
							{color.name}
							<sup class='w-4' title={`Gruppe ${color.result.group}`}> {color.result.group} </sup>
						</span>
						{@render result( section, color.result )}
					</div>
				{/each}
			{/each}
		{/each}
	{/if}
</div>




{#snippet result( section, result )}
	<span class='w-12 number'>{ dec( result.year ) }</span>
	<span class='w-12 number'>{ dec( result.breeders ) }</span>
	<span class='w-12 number'>{ dec( result.pairs ) }</span>
	<span class='w-2'></span>
	<span class='w-12 number'>{ dec( result.lay.eggs, 0 ) }</span>
	<span class='w-14 number'>{ dec( result.lay.weight, 1 ) }</span>
	<span class='w-2'></span>
	{#if section.id === 5}
		<span class='w-10 number'>{ result.brood.hatched}</span>
		<span class='w-10 number'>{ result.pairs}</span>
		<span class='w-10 number'>{ result.pairs?result.brood.hatched/result.pairs:null}</span>
	{:else}
		<span class='w-10 number'>{ result.brood.eggs}</span>
		<span class='w-10 number'>{ result.brood.fertile}</span>
		<span class='w-10 number'>{ result.brood.hatched}</span>
	{/if}
	<span class='w-2'></span>
	<span class='w-10 number'>{ result.show.count}</span>
	<span class='w-12 number'>{ dec( result.show.score, 1 ) }</span>
	{#if result.breeder }
		<a class='w-10 text'
		   href={`/moderator/${result.districtId}/breeder/${result.breeder.id}/pair/${result.pairId}`}
		   title={`Züchter ${result.breeder.firstName} ${txt(result.breeder.infix)} ${result.breeder.lastName}`}
		>

			{ result.breeder.firstName.substring(0,1)}.{ result.breeder.lastName.substring(0,1)}
		</a>
	{:else}
		<span class='w-10'></span>
	{/if}
{/snippet}


<style>
	h3 {
		@apply text-center text-xl bg-teal-200 font-bold sticky top-0;
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
	.pair {
		@apply bg-teal-50;
	}

	span {
		@apply align-bottom;
	}
</style>