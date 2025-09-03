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

	let { report } = $props();

	const types = { // what to report
		2: {id: 2, name: 'Zuchten'},
		10: {id: 10, name: 'Legeleistung'},
		20: {id: 20, name: 'Brutleistung Leger'},
		21: {id: 21, name: 'Brutleistung Tauben'},
		30: {id: 30, name: 'Schauleistung'}
	};

	let district = $derived( ctx.federation.districts[ ctx.report.args.district ] );

	function onTypeChange( event ) {
		let url = new URL( page.url );
		url.searchParams.set( 'type', event.target.value );
		goto( url.href );
	}

</script>

{#key report }

	<Filter {report} />
	{#if report !== null}
		<Chart {district} report={report.chart} year={report.args.year} />
	{/if}

	{#if report !== null }
		<div class='flex flex-col break-after-page' open>
			<header class='border-header bg-header text-header'>Leistungen im Übersicht</header>
			<div class='mt-2 flex flex-row justify-evenly'>
				<Select class='w-64' label='Leistung' value={report.args.type} onchange={onTypeChange}>
					{#each Object.values( types ) as type}
						<option value={type.id}>{type.name}</option>/
					{/each}
				</Select>
			</div>

			<div class='flex flex-row justify-evenly p-4 gap-x-2 '>
				<div class='flex flex-col'>
					<header>Trend für {district.name}</header>
					{#if report.trend}
						<Trend report={report.trend} typeId={report.args.type} />
					{/if}
				</div>
				<div class='flex flex-col'>
					<header>Verteilung in {report.args.year}</header>
					{#if report.map}
						<Map report={report.map} typeId={report.args.type} />
					{/if}
				</div>
			</div>
		</div>
	{/if}

	{#if report !== null }
		<div class='flex flex-col break-after-page' open>
			<header class=''>Leistungsdaten im {district.name} für {report.args.year}</header>
			<div class='flex flex-col py-4'>
				<Table table={report.table} {district} year={report.args.year} />
			</div>
		</div>
	{/if}
{/key}

<style>
	header {
		@apply px-4 py-2 text-xl text-center sticky top-1;
	}
    summary {
        @apply px-4 py-2 bg-teal-200 text-xl font-bold sticky top-0 text-center;
    }
</style>

