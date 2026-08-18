<script>
	import { fade, slide } from 'svelte/transition';
	import { goto, invalidate } from '$app/navigation';
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import { dec, selectName } from '$lib/js/tools.js';

	let { district, pairs } = $props();

	let years = []; // create years array for select
	for( let year=+( new Date().getFullYear() )+1; year>=1980; year-- ) years.push( year ); // Todo, move to tools as function ?

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

	function onYearChange( event ) {
		const year = event.target.value;
		let url = new URL( page.url ); // needed to reload
		url.searchParams.set( 'year', year );
		goto( url.href );
	}

</script>


<!--div class='flex flex-row justify-end gap-x-4'>
	<a href={`${page.url.href}/0`}>[+]</a>
</div-->

{#if ctx.year }
	<!--div class='flex flex-row border-header bg-header text-header text-xl p-2 pt-1 gap-x-2 justify-center sticky top-1' in:fade>
		<span class='pt-5 font-bold'>Stammleistung eingeben für {ctx.year}</span>
		<div class='flex flex-col'>
			<span class='text-xs pl-2'>Jahr</span>
			<select class='border-header bg-white' label='Jahr' value={ctx.year} onchange={onYearChange}>
				{#each years as year}
					<option value={year}>{year}</option>
				{/each}
			</select>
		</div>
	</div-->

{/if}


<h2 class='text-center'>Züchter mit deren Stämme/Paare</h2>

<!--header class='flex flex-row border-header bg-header text-header section items-end px-2 py-0 pl-6 gap-x-2 screen:sticky screen:top-1'>
	<span class='w-12' title='Sortieren' role='button' onclick={onSortByNumber}>ZbNr</span>
	<span class='w-80' title='Sortieren' role='button' onclick={onSortByName}>Name</span>
	<span class='w-48' title='Sortieren' role='button' onclick={onSortByClub}>Ortverein</span>
	<span class='w-12' title='Sortieren' role='button' onclick={onSortByActive}>Online</span>
</header -->

<main class='flex flex-col p-4'>
	{#if pairs}
		{#each pairs as breeder }
			<!--a class='flex flex-row' href={page.url.pathname+'/'+breeder.id+'/pair?year='+ctx.year}>
				<span class='w-12'> {breeder.member} </span>
				<span class='w-80'> {breeder.lastname}, {breeder.firstname} {breeder.infix} </span>
				<span class='w-48'> {breeder.club} </span>
				<span class='w-12 text-green-600'> {breeder.active?'✓':'.'} </span>
			</a -->
			<details class=''>
				<summary class='flex flex-row'>
					<span class='inline-block w-16'> {breeder.member} </span>
					<span class='inline-block w-80'> {breeder.lastname}, {breeder.firstname} {breeder.infix} </span>
					<span class='inline-block w-8'> {breeder.pairs.length}</span>
					<span class='inline-block grow'></span>
					<a class='w-12' href={'/moderator/district/6/result/pair/0?breeder='+breeder.id+'&year='+ctx.year}>[ + ]</a>
					<!--http://localhost:5173/moderator/district/6/result/breeder/217/pair/0 -->
				</summary>
		  		<div class='flex flex-col' in:fade>
					  {#each breeder.pairs as pair}
					  <!-- href='http://localhost:5173/moderator/district/6/result/breeder/1/pair/36' -->
						  <a class='pl-6 flex flex-row' href={page.url.pathname+'/'+pair.id}>
							  <span class='w-16'>{pair.name}</span>
							  <span class='w-64 whitespace-nowrap'>{ctx.standard.breeds[ pair.breedId].name}</span>
							  <span class='w-64 whitespace-nowrap'>{ctx.standard.colors[ pair.colorId].name}</span>
							  <span class='grow'></span>
							  <span class='w-16 px-1 text-right'>{ dec( pair.lay.eggs ) }</span> ,
							  <span class='w-16 px-1 text-right'>{ dec( pair.lay.weight ) }</span> |
							  <span class='w-16 px-1 text-right'>{ pair.brood.eggs }</span> ,
							  <span class='w-16 px-1 text-right'>{ pair.brood.fertile }</span> ,
							  <span class='w-16 px-1 text-right'>{ pair.brood.hatched }</span> |
							  <span class='w-16 px-1 text-right'>{ pair.show.count }</span> ,
							  <span class='w-16 px-1 text-right'>{ dec( pair.show.score, 1 ) }</span>
						  </a>
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
		content: "▶ ";
	}
	details[open] summary::marker {
		content: "▼ ";
	}


    h3a {
        @apply text-center text-xl bg-teal-200 font-bold sticky top-0;
    }
    pa {
        @apply text-center italic;
    }
    aa {
        @apply border-b flex flex-row p-2 pl-6 gap-x-2;
    }

</style>