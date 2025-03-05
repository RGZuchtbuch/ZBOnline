<script>
	import { goto } from '$app/navigation';
	import { dec } from '$lib/js/toolbox.js';
	let { data } = $props();

	let years = [];
	let nextYear = +( new Date().getFullYear() )+1;
	for( let year=nextYear; year>=1980; year-- ) {
		years.push( year );
	}


	function onYearChange( event ) {
		const year = +event.target.value;
		data.url.searchParams.set( 'year', year );
		goto( data.url );
		//goto( `./${year}`);
	}

	// $effect( () => {
	// 	goto( `./${year}`);
	// })

</script>


<h3 class=''>Eingegebene Leistungen im {data.district.short}
	<select class='border border-teal-600 border-1 bg-inherit' value={data.year} onchange={onYearChange}>
		{#each years as y}
			<option value={y}>{y}</option>
		{/each}
	</select>
</h3>

<p class='info'>
	Leistungen können als gesamt Leistung für einem Verband eingegeben werden, oder als einzelne Meldungen beim Züchter.<br>
	Hier eine Liste von alle Eingaben.
</p>

<div class='flex flex-col'>
	{#each data.results.sections as section}
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
				<div class='flex flex-row pl-8' class:pair={color.result.pairId !== null}>
					<span class='grow italic'>{color.name}</span>
					{@render result( color.result )}
				</div>
			{/each}
		{/each}
	{/each}
</div>

{#snippet result( result )}
	<span class='w-12 number'>{ dec( result.year ) }</span>
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
		<a class='w-10 text' href={`/moderator/${result.districtId}/breeder/${result.breeder.id}/pair/${result.pairId}`}>

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