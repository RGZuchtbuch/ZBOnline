<script>
	import {fade} from 'svelte/transition';
	import { goto } from '$app/navigation';
	import { page } from '$app/state';
	import store, { federation, standard } from '$lib/js/store.svelte.js';
	import Filter from './view/Filter.svelte';
	import LayBar from './view/LayBar.svelte';
	import BroodBarLayers from './view/BroodBarLayers.svelte';
	import BroodBarPigeons from './view/BroodBarPigeons.svelte';
	import ShowBar from '$lib/cmp/report/view/ShowBar.svelte';
	import Map from '$lib/cmp/report/view/Map.svelte';
	import Table from '$lib/cmp/report/view/Table.svelte';
	import Trend from '$lib/cmp/report/view/Trend.svelte';
	import Select from '$lib/cmp/form/input/Select.svelte';

	let { query, chart, map, trend, table } = $props();

	const types = {
		2: {id: 2, name: 'Zuchten'},
		10: {id: 10, name: 'Legeleistung'},
		20: {id: 20, name: 'Brutleistung Leger'},
		21: {id: 21, name: 'Brutleistung Tauben'},
		30: {id: 30, name: 'Schauleistung'}
	};

	let type     = $derived( types[ typeId ] );
	let district = $derived( $federation.districts[ query.district || 1 ] );
	let year     = $derived( query.year || new Date().getFullYear()-1 );

	function onTypeChange( event ) {
		let url = new URL( page.url );
		url.searchParams.set( 'type', event.target.value );
		console.log('event', event.target.value );
		goto( url.href );
	}
</script>


<h3 class='header'>Zuchtleistungen</h3>

<!--Filter class='print:hidden' {...data} bind:district={district} bind:year={year} bind:group={group} bind:section={section} bind:breed={breed} bind:color={color} bind:type={type}/-->
<Filter />

{#if chart}
	<div class='flex flex-col' open>
		<header class=''>Gesamt Leistungen im {district.name} in {year}</header>
		<div class='flex flex-row justify-evenly py-4'>
			<LayBar class='' report={chart} />
			<BroodBarLayers report={chart} />
			<BroodBarPigeons report={chart} />
			<ShowBar report={chart} />
		</div>
	</div>
{/if}

{#if map && trend}
	<div class='flex flex-col break-after-page' open>
		<header class=''>Leistungen im Übersicht</header>
		<div class='mt-2 flex flex-row justify-evenly'>
			<Select class='w-64' label='Leistung' value={query.type} onchange={onTypeChange}>
				{#each Object.values( types ) as type}
					<option value={type.id}>{type.name}</option>/
				{/each}
			</Select>
		</div>
		<div class='flex flex-row justify-evenly p-4 gap-x-2 '>
			<div class='flex flex-col'>
				<header>Trend für {district.name}</header>
				<Trend report={trend} typeId={query.type} />
			</div>
			<div class='flex flex-col'>
				<header>Verteilung in {year}</header>
				<Map report={map} typeId={query.type} />
			</div>
		</div>
	</div>
{/if}

{#if table}
	<div class='flex flex-col break-after-page' open>
		<header class=''>Leistungsdaten im {district.name} für {year}</header>
		<div class='flex flex-col py-4'>
			<Table report={table} {district} {year} />
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

