<script>
	import { fade, slide } from 'svelte/transition';
	import { goto, invalidate } from '$app/navigation';
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import { dec, selectName } from '$lib/js/tools.js';
	import BreedersResultForm from '$lib/cmp/result/BreederResultForm.svelte';


	let { district, breeders } = $props();

	let years = []; // create years array for select
	for( let year=+( new Date().getFullYear() )+1; year>=1980; year-- ) years.push( year ); // Todo, move to tools as function ?

	//let rootSection = $state( ctx.standard.rootSections.find( ( section ) => section.id === pair.sectionId ) ?? null );
	//let breed   = $state( ctx.standard.breeds[ pair.breedId ] ?? null );
	//let color   = $state( ctx.standard.colors[ pair.colorId ] ?? null );

	//let breedId = $state( pair.breedId );

	function onSortByNumber() {
		breeders.sort( (a, b) => a.member - b.member );
	}
	function onSortByName() {
		breeders.sort( (a, b) => (''+selectName( a )).localeCompare( selectName(b) ) );
	}
	function onSortByClub() {
		breeders.sort( (a, b) => (''+a.club).localeCompare( b.club ) );
	}
	function onSortByStart() {
		breeders.sort( (a, b) => (''+a.start).localeCompare( b.start ) );
	}
	function onSortByEnd() {
		breeders.sort( (a, b) => (''+a.end).localeCompare( b.end ) );
	}
	function onSortByActive() {
		breeders.sort( (a, b) => (''+a.active).localeCompare( b.active ) );
	}

//	function onYearChange( event ) {
//		const year = event.target.value;
//		let url = new URL( page.url ); // needed to reload
//		url.searchParams.set( 'year', year );
//		goto( url.href );
//	}



	function onAddResult( breeder ) {
		return ( event ) => {
			console.log( 'Adding result' );
			const new_breeding = newBreeding( district.id, breeder.id, ctx.year );
			breeder.results.push( new_breeding );
			console.log( 'Breeder after new', breeder );
		}
	}

	function newBreeding( districtId, breederId, year ) {
		return {
			"id": 0,
			"breederId": breederId,
			"pairId": null,
			"breedingId": breederId,
			"districtId": districtId,
			"year": year,
			"group": "I",
			"sectionId": null,
			"breedId": null,
			"colorId": null,
			"breeders": 1,
			"pairs": null,
			"lay": {
				"eggs": null,
				"weight": null
			},
			"brood": {
				"eggs": null,
				"fertile": null,
				"hatched": null
			},
			"show": {
				"count": null,
				"score": null
			}
		};
	}



</script>


<!--div class='flex flex-row justify-end gap-x-4'>
	<a href={`${page.url.href}/0`}>[+]</a>
</div-->

{#if false && ctx.year }
	<div class='flex flex-row border-header bg-header text-header text-xl p-2 pt-1 gap-x-2 justify-center sticky top-1' in:fade>
		<span class='pt-5 font-bold'>Zuchtleistungen eingeben für {ctx.year}</span>
	</div>

{/if}


<h2 class='text-center'>Eingabe pro Zucht vom Züchter</h2>

<!--header class='flex flex-row border-header bg-header text-header section items-end px-2 py-0 pl-6 gap-x-2 screen:sticky screen:top-1'>
	<span class='w-12' title='Sortieren' role='button' onclick={onSortByNumber}>ZbNr</span>
	<span class='w-80' title='Sortieren' role='button' onclick={onSortByName}>Name</span>
	<span class='w-48' title='Sortieren' role='button' onclick={onSortByClub}>Ortverein</span>
	<span class='w-12' title='Sortieren' role='button' onclick={onSortByActive}>Online</span>
</header -->

<main class='flex flex-col pl-2'>

	<div class='flex flex-row pl-4'>
		<span class='w-16'>Mitg.</span>
		<span class='w-128'>Züchter</span>
		<span class='w-8'>Zuchten</span>
	</div>
	{#if breeders}
		{#each breeders as breeder }
			<!--a class='flex flex-row' href={page.url.pathname+'/'+breeder.id+'/pair?year='+ctx.year}>
				<span class='w-12'> {breeder.member} </span>
				<span class='w-80'> {breeder.lastname}, {breeder.firstname} {breeder.infix} </span>
				<span class='w-48'> {breeder.club} </span>
				<span class='w-12 text-green-600'> {breeder.active?'✓':'.'} </span>
			</a -->
			<details id={breeder.id} class=''>
				<summary class='flex flex-row'>
					<span class='inline-block w-16'> {breeder.member} </span>
					<span class='inline-block w-128'> {breeder.lastname}, {breeder.firstname} {breeder.infix} </span>
					<span class='inline-block w-8'> {breeder.results.length}</span>
					<span class='inline-block grow'></span>
					<span class='w-12' onclick={ onAddResult( breeder ) }>[ + ]</span>
					<!--http://localhost:5173/moderator/district/6/result/breeder/217/pair/0 -->
				</summary>
		  		<div class='pl-0 flex flex-col' in:fade>
					{#each breeder.results as result, i}
						<BreedersResultForm bind:result={ breeder.results[ i ] }></BreedersResultForm>
					{/each}
				</div>
			</details>
		{/each}
	{/if}

</main>

<style>
	summary {
		display: list-item;
	}
	summary::marker {
		content: "▶";
	}
	details[open] {
		@apply bg-teal-50;
	}
	details[open] summary::marker {
		content: "▼";
	}

</style>