<script>
	import {fade} from 'svelte/transition';
	import { goto } from '$app/navigation';
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Filter from './view/Filter.svelte';
	import Map from '$lib/cmp/report/view/Map.svelte';
	import Table from '$lib/cmp/report/view/Table.svelte';
	import Trend from '$lib/cmp/report/view/Trend.svelte';
	import Select from '$lib/cmp/form/input/Select.svelte';
	import Chart from '$lib/cmp/report/view/Chart.svelte';

	let { args, report, federation, standard } = $props();

	const types = { // what to report
		2: {id: 2, name: 'Zuchten'},
		10: {id: 10, name: 'Legeleistung'},
		20: {id: 20, name: 'Brutleistung Leger'},
		21: {id: 21, name: 'Brutleistung Tauben'},
		30: {id: 30, name: 'Schauleistung'}
	};

	//let type     = $derived( types[ +page.url.searchParams.get('type') || 2 ] );
	let district = $derived( federation.districts[ args.district ] );
	//let year     = $derived( +page.url.searchParams.get('year') || new Date().getFullYear()-1 );

	function onTypeChange( event ) {
		let url = new URL( page.url );
		url.searchParams.set( 'type', event.target.value );
		goto( url.href );
	}

</script>


<Filter {args} {federation} {standard} />

{#if report && report.chart}
	<Chart {district} report={report.chart} year={args.year} />
{/if}

{#if report }
	<div class='flex flex-col break-after-page' open>
		<header class=''>Leistungen im Übersicht</header>
		<div class='mt-2 flex flex-row justify-evenly'>
			<Select class='w-64' label='Leistung' value={args.type} onchange={onTypeChange}>
				{#each Object.values( types ) as type}
					<option value={type.id}>{type.name}</option>/
				{/each}
			</Select>
		</div>

		<div class='flex flex-row justify-evenly p-4 gap-x-2 '>
			<div class='flex flex-col'>
				<header>Trend für {district.name}</header>
				{#if report.trend}
					<Trend report={report.trend} typeId={args.type} />
				{/if}
			</div>
			<div class='flex flex-col'>
				<header>Verteilung in {args.year}</header>
				{#if report.map}
					<Map report={report.map} typeId={args.type} />
				{/if}
			</div>
		</div>
	</div>
{/if}

{#if report && report.table}
	<div class='flex flex-col break-after-page' open>
		<header class=''>Leistungsdaten im {district.name} für {args.year}</header>
		<div class='flex flex-col py-4'>
			<Table report={report.table} {district} year={args.year} />
		</div>
	</div>
{/if}


<style>
	header {
		@apply px-4 py-2 bg-teal-200 text-xl font-bold sticky top-0 text-center;
	}
    summary {
        @apply px-4 py-2 bg-teal-200 text-xl font-bold sticky top-0 text-center;
    }
</style>

