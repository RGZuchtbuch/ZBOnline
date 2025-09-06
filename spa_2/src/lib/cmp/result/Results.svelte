<script>
	//import './src/app.css'; // need this once on highest level
	import { goto } from '$app/navigation';
	import { page } from '$app/state';
	import Result from '$lib/cmp/result/results/Result.svelte';

	let { district, year, results } = $props();

	let years = [];
	let nextYear = +( new Date().getFullYear() )+1;
	for( let year=nextYear; year>=1980; year-- ) {
		years.push( year );
	}

	function onYearChange( event ) {
		//year = event.target.value;
		let url = new URL( page.url ); // page.url is immutable
		url.searchParams.set( 'year', event.target.value );
		goto( url.href );
	}

</script>


{#if district && year && results}
	<div class='flex flex-row border-header bg-header text-header text-xl justify-center gap-x-2 p-2 pt-1 sticky top-1'>
		<!--span class='grow flex flex-row justify-center'-->
		<span class='pt-5 '>Leistungen für</span>
		<div class='flex flex-col'>
			<span class='label'>Jahr</span>
			<select class='border border-header bg-white' value={year} onchange={onYearChange}>
				{#each years as y}
					<option value={y}>{y}</option>
				{/each}
			</select>
		</div>
		<!--/span-->

	</div>

	<p class='grow info'>
		Leistungen können als gesamt Leistung für einem Verband eingegeben werden, oder als einzelne Meldungen beim Züchter.<br>
		Hier eine Liste von alle Eingaben in alle Zuchtbuchgruppen (I, II, III).
	</p>
	<!--div class='flex flex-row justify-end'> <a class='pt-2' href={`/moderator/${district.id}/result/edit?year=${year}`}>[Edit]</a></div-->

	{#if district && year && results}
		{#if district && year && results && results.sections}
			{#if results.sections.length === 0}
				<div class='text-center'>Leider noch keine Eingaben</div>
			{:else}
				<div class='flex flex-col'>
					{#each results.sections as section}
						<div class='flex flex-row bg-slate-100 border mt-4 py-1 items-end sticky top-16'>
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

									<span class='w-16' title='Züchter wenn als Stamm eingegeben'>Stamm</span>
								</span>
							</span>
						</div>

						{#each section.breeds as breed}
							{#if breed.result} <!-- clickable to edit-->
								<a class='flex flex-row pl-4'
								   class:accepted={breed.result && breed.result.pairId && breed.result.accepted}
								   class:not-accepted={breed.result && breed.result.pairId && !breed.result.accepted}
								   href={breed.result.pairId
									? `${page.url.pathname}/breeder/${breed.result.breeder.id}/pair/${breed.result.pairId}`
								    : `/moderator/district/6/result/district?year=${breed.result.year}&section=${breed.result.rootSectionId}&group=${breed.result.group}` }>
								>
									<span class='w-4'></span>
									<span class='grow'>
									<span>{breed.name}</span>
										{#if breed.result}
											<sup class='' title={`Gruppe ${breed.result.group}`}> {breed.result.group} </sup>
										{/if}
									</span>
									{#if breed.result}
										<Result section result={breed.result} />
									{/if}
								</a>
							{:else}
								<div class='flex flex-row pl-4'	>
									<span class='w-4'></span>
									<span class='grow'>
									<span>{breed.name}</span>
										{#if breed.result}
										<sup class='' title={`Gruppe ${breed.result.group}`}> {breed.result.group} </sup>
									{/if}
								</span>
									{#if breed.result}
										<Result section result={breed.result} />
									{/if}
								</div>
							{/if}


							{#each breed.colors as color}
								<a class='flex flex-row pl-10'
									class:accepted={color.result.pairId && color.result.accepted}
								    class:not-accepted="{color.result.pairId && !color.result.accepted}"
								    href={color.result.pairId
								        ? `${page.url.pathname}/breeder/${color.result.breeder.id}/pair/${color.result.pairId}`
								        : `/moderator/district/6/result/district?year=${color.result.year}&section=${color.result.rootSectionId}&color=${color.result.colorId}&group=${color.result.group}` }>
								>
									<span class='w-4'></span>
									<span class='grow italic'>
										{color.name}
										<sup class='' title={`Gruppe ${color.result.group}`}> {color.result.group} </sup>
									</span>
									<Result {section} result={color.result} />
								</a>
							{/each}
						{/each}
					{/each}

				</div>

			{/if}
		{/if}
	{/if}
{/if}

<style>
	h3 {
		@apply text-center text-2xl border-header bg-header text-header;
	}
	p.info {
		@apply px-8 py-4 text-center;
	}
	span {
		@apply align-bottom;
	}

	.label {
        line-height: 1.0em;
        font-size: 0.6em;
        padding-left: 0.5em;
	}

    .accepted {
        @apply bg-teal-50;
    }
    .not-accepted {
        @apply bg-orange-100;
    }
</style>