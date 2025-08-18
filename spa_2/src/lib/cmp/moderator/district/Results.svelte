<script>
	//import './src/app.css'; // need this once on highest level
	import { goto } from '$app/navigation';
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import { dec, txt } from '$lib/js/tools.js';

	import { Select } from '$lib/cmp/form/Form.svelte';
	import ResultsView from './results/ResultsView.svelte';

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


{#key district && year && results}
	<div class='flex flex-row border-header bg-header text-header text-xl justify-center gap-x-2 p-1 sticky top-1'>
		<span class='pt-2 '>Leistungen für</span>
		<select class='w-24 border border-header bg-header text-header error=null' value={year} onchange={onYearChange}>
			{#each years as y}
				<option value={y}>{y}</option>
			{/each}
		</select>
	</div>

	<div class ='flex flex-row'>
		<p class='grow info'>
			Leistungen können als gesamt Leistung für einem Verband eingegeben werden, oder als einzelne Meldungen beim Züchter.<br>
			Hier eine Liste von alle Eingaben in alle Zuchtbuchgruppen (I, II, III).
		</p>
	</div>

	{#if district && year && results}
		<ResultsView {district} {year} {results} />
	{/if}
{/key}

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
</style>