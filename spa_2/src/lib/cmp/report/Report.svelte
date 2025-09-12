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

	import Trend2 from '$lib/cmp/report/view/Trend2.svelte';

	let { report } = $props();

	const types = { // what to report
		2: {id: 2, name: 'Zuchten'},
		10: {id: 10, name: 'Legeleistung'},
		20: {id: 20, name: 'Brutleistung Leger'},
		21: {id: 21, name: 'Brutleistung Tauben'},
		30: {id: 30, name: 'Schauleistung'}
	};

	const units = {
		breeders     : { id:'breeders', name:'Zuchten' },
		lay          : { id:'lay', name:'Legeleistung' },
			'lay.eggs'   : { id:'lay.eggs', name:'Eier/Jahr %'},
			'lay.weight' : { id:'lay.weight', name:'Eiergewicht %' },
		brood        : { id:'brood', name:'Brutleistung' },
			'brood.eggs'       : { id:'brood.egs', name:'Eingelegt'},
			'brood.broods'     : { id:'brood.broods', name:'Bruten (Tauben)'},
			'brood.fertile'    : { id:'brood.fertile', name:'Befruchtung % (Geflügel)'},
			'brood.hatched'    : { id:'brood.hatched', name:'Schlupf % (*)'},
			'brood.production' : { id:'brood.production', name:'Küken / Paar {Tauben)'},
		show : { id:'show', name:'Schauleistung' },
			'show.count'      : { id:'show.count', name:'Ausgestellten Tiere'},
			'show.score'      : { id:'show.score', name:'Bewertung'},
	}

	let unit = $state( units[ page.url.searchParams.get( 'unit' ) ] );
	let district = $derived( ctx.federation.districts[ ctx.report.args.district ] );

	function onTypeChange( event ) {
		let url = new URL( page.url );
		url.searchParams.set( 'type', event.target.value );
		goto( url.href );
	}

	function onUnitChange( event ) {
		//const unit = event.target.value;
		let url = new URL( page.url );
		url.searchParams.set( 'unit', unit.id );
		goto( url.href );
	}

	$inspect( 'Unit', unit );

</script>

{#if report }
	<Filter {report} />

	<div class='flex flex-col break-after-page'>
		<header class='border-header bg-header text-header'>Gesamtleistungen im {district.name} in {report.args.year}</header>
		<Chart {district} report={report.chart} year={report.args.year} />

		<header class='border-header bg-header text-header'>Leistungen im Übersicht</header>
		<div class='mt-2 flex flex-row justify-evenly'>
			<Select class='w-64' label='Leistung' value={report.args.type} onchange={onTypeChange}>
				{#each Object.values( types ) as type}
					<option value={type.id}>{type.name}</option>/
				{/each}
			</Select>

			<Select class='w-64' label='Leistung 2' bind:value={unit} onchange={onUnitChange}>
				{#each Object.values( units ) as unit}
					<option value={unit}>{#if unit.id.includes( '.' ) }▸&nbsp;{/if} {unit.name}</option>/
				{/each}
			</Select>

		</div>



		<div class='flex flex-col justify-evenly gap-x-2 '>
			<div class='flex flex-col'>
				<header>Verteilung in {report.args.year}</header>
				{#if report.map}
					<div class='flex flex-row justify-center'>
						<Map report={report.map} typeId={report.args.type} />
					</div>
				{/if}
			</div>

			<div class='flex flex-col'>
				<header>Trend für {district.name}</header>
				{#if report.trend}
					<!--Trend report={report.trend} typeId={report.args.type} /-->
					<Trend2 label='Zuchten'  data={report.trend.years} unit='breeders' />

					<Trend2 label='Legeleistung Eier / Jahr'  data={report.trend.years} unit='layEggs' factor={100} />
					<Trend2 label='Legeleistung Eiergewicht %'  data={report.trend.years} unit='layWeight' factor={100} />
					<hr />
					<Trend2 label='Brutleistung Geflügel, Eingelegte Eier'  data={report.trend.years} unit='broodLayerEggs' factor={100} />
					<Trend2 label='Brutleistung Geflügel, Befruchtet %'  data={report.trend.years} unit='broodLayerFertile' factor={100} />
					<Trend2 label='Brutleistung Geflügel, Geschlüpft %'  data={report.trend.years} unit='broodLayerHatched' factor={100} />

					<Trend2 label='Brutleistung Tauben, Nester'  data={report.trend.years} unit='broodPigeonEggs' factor={0.5} />
					<Trend2 label='Brutleistung Tauben, Geschlüpft %'  data={report.trend.years} unit='broodPigeonHatched' scale={{min:0, max:100}} factor={100} />
					<Trend2 label='Brutleistung Tauben, Küken / Paar'  data={report.trend.years} unit='broodPigeonResult' />

					<Trend2 label='Schauleistung, Ausgestellten Tiere'  data={report.trend.years} unit='showCount' />
					<Trend2 label='Schauleistung, Bewertung'  data={report.trend.years} unit='showScore' scale={{min:89, max:97}}/>

				{/if}

			</div>

		</div>
	</div>

	<hr class='mt-4'/>
	<div class='flex flex-col break-after-page'>
		<header class=''>Leistungsdaten im {district.name} für {report.args.year}</header>
		<div class='flex flex-col py-4'>
			<Table table={report.table} {district} year={report.args.year} />
		</div>
	</div>
{:else}
	Bericht wird geladen...
{/if}

<style>
	header {
		@apply px-4 py-2 border-header bg-header text-header text-center text-xl font-bold screen:sticky screen:top-1;
	}
    summary {
        @apply px-4 py-2 bg-teal-200 text-xl font-bold sticky top-0 text-center;
    }
</style>

