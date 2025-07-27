<script>
	import { goto } from '$app/navigation';
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import { dec, txt } from '$lib/js/tools.js';

	import { Select } from '$lib/cmp/form/Form.svelte';
	import ResultsView from './results/ResultsView.svelte';

	let { district, year, results } = $props();

	console.log( 'Y', results );

//	let edit = $state( false );
//	let authorized = $derived( store.user && ( store.user.id === district.moderator.id || store.user.admin ) ); // can edit

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

R
{#key district && year && results}
	<h3 class=''>Leistungen für
		<select class='w-24 border border-teal-600 border-1 bg-inherit error=null' value={year} onchange={onYearChange}>
			{#each years as y}
				<option value={y}>{y}</option>
			{/each}
		</select>
	</h3>

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