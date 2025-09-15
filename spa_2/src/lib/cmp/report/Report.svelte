<script>
	import {fade} from 'svelte/transition';
	import { goto } from '$app/navigation';
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Filter from './view/Filter.svelte';
	import Map from '$lib/cmp/report/view/Map2.svelte';
	import Table from '$lib/cmp/report/view/Table.svelte';
	import Trend from '$lib/cmp/report/view/Trend2.svelte';
	import Select from '$lib/cmp/form/input/Select.svelte';
	import Chart from '$lib/cmp/report/view/Chart.svelte';

	let { report } = $props();

	// const types = { // what to report
	// 	2: {id: 2, name: 'Zuchten'},
	// 	10: {id: 10, name: 'Legeleistung'},
	// 	20: {id: 20, name: 'Brutleistung Leger'},
	// 	21: {id: 21, name: 'Brutleistung Tauben'},
	// 	30: {id: 30, name: 'Schauleistung'}
	// };

	const units = {
		breeders               : { id:'breeders', name:'Zuchten', factor:1 },

		layBreeders            : { id:'layBreeders', name:'▸ Zuchten für Legeleistung', factor:1 },
			layEggs            : {id: 'layEggs', name: '\xA0\xA0\xA0 ▸ Legeleistung, Eier/Jahr %', factor:100 },
			layWeight          : {id: 'layWeight', name: '\xA0\xA0\xA0 ▸ Legeleistung, Eiergewicht %', factor:100 },

		broodLayerBreeders     : { id:'broodLayerBreeders', name:'▸ Geflügel Brutleistung, Zuchten', factor:1 },
			broodLayerEggs     : { id:'broodLayerEggs', name:'\xA0\xA0\xA0 ▸ Geflügel Brutleistung, Eingelegt', factor:1},
			broodLayerFertile  : { id:'broodLayerFertile', name:'\xA0\xA0\xA0 ▸ Geflügel Brutleistung, Befruchtung %', factor:100 },
			broodLayerHatched  : { id:'broodLayerHatched', name:'\xA0\xA0\xA0 ▸ Geflügel Brutleistung, Schlupf %', factor:100 },

		broodPigeonBreeders    : { id:'broodPigeonBreeders', name:'▸ Tauben Brutleistung, Zuchten', factor:1 },
			broodPigeonEggs    : { id:'broodPigeonEggs', name:'\xA0\xA0\xA0 ▸ Tauben Brutleistung, Gelegt', factor:1},
			broodPigeonHatched : { id:'broodPigeonHatched', name:'\xA0\xA0\xA0 ▸ Tauben Brutleistung, Schlupf %', factor:100 },
			broodPigeonResult  : { id:'broodPigeonResult', name:'\xA0\xA0\xA0 ▸ Tauben Brutleistung, Küken / Paar', factor:1 },


		showBreeders           : { id:'showBreeders', name:'▸ Schauleistung, zuchten', factor:1 },
			showCount          : { id:'showCount', name:'\xA0\xA0\xA0 ▸ Schauleistung, Ausgestellten Tiere', factor:1 },
			showScore          : { id:'showScore', name:'\xA0\xA0\xA0 ▸ Schauleistung, Note', factor:1 },
	}

//	let unit = $state( units[ page.url.searchParams.get( 'unit' ) || 'breeders' ] );
	let unitId = $state( page.url.searchParams.get( 'unit' ) || 'breeders' );
	let unit = $derived( units[ unitId ] ?? null );
	$inspect( 'Unit', unit );
	let district = $derived( ctx.federation.districts[ ctx.report.args.district ] );

	// function onTypeChange( event ) {
	// 	let url = new URL( page.url );
	// 	url.searchParams.set( 'type', event.target.value );
	// 	goto( url.href );
	// }

	function onUnitChange( event ) {
		const id = event.target.value;
		console.log( 'Unit', id );
		let url = new URL(page.url);
		url.searchParams.set('unit', id);
		goto(url.href);
	}

	let section = $derived( ctx.standard.sections[ +page.url.searchParams.get( 'section' ) ] );
	let breed   = $derived( ctx.standard.breeds[ +page.url.searchParams.get( 'breed' ) ] );
	let color   = $derived( ctx.standard.colors[ +page.url.searchParams.get( 'color' ) ] );
	let group   = $derived( page.url.searchParams.get( 'group') );

	//$inspect( 'Section', section );

</script>

{#if report }
	<Filter {report} />

	<div class='flex flex-col break-after-page'>
		<header class='border-header bg-header text-header'>Gesamtleistungen im {district.name} in {report.args.year}</header>
		<Chart {district} report={report.chart} year={report.args.year} />

		<header class='border-header bg-header text-header'>Leistungen im Übersicht</header>
		<div class='mt-2 flex flex-row justify-evenly'>
			<Select class='' label='Leistung' value={unitId} onchange={onUnitChange}>
				{#each Object.values( units ) as unit}
					<option value={unit.id}>{unit.name}</option>
				{/each}
			</Select>
		</div>



		<div class='flex flex-col justify-evenly gap-x-2 '>
			<div class='flex flex-col'>
				<header>Verteilung in {report.args.year}</header>
				{#if report.map && unit }
					<div class='flex flex-row justify-center'>
						<!--Map report={report.map} typeId={report.args.type} /-->
						<Map title={units[ unitId ].name} districts={report.map.districts} {unit}/>
					</div>
				{/if}
			</div>
		</div>
	</div>


	<div class='flex flex-col break-after-page'>
		<div>
			<div class='flex flex-col'>
				<header>
					<span>Trend für {district.name}</span>
				</header>
				<div class='bg-white font-bold text-center'>
					<span>{section ? section.name : 'Alle Sparten, Rassen und Farbenschläge'}</span>
					<span>{breed   ? `, ${breed.name}`    : ', Alle Rassen'}</span>
					<span>{color   ? `, ${color.name}`   : ', Alle Farbenschläge'}</span>
					<span>{group   ? `, Zuchtbuchgruppe ${group}` : ', Alle Zuchtbuchgruppen'}</span>
				</div>
				{#if report.trend}
					<div class='my-2 p-1 bg-slate-200 font-bold text-center'>Gemeldete Zuchten</div>
					<div class='flex flex-row justify-evenly'>
					<!--Trend report={report.trend} typeId={report.args.type} /-->
						<Trend title='Zuchten'  data={report.trend.years} unit='breeders' color={{fill:'#FAA', border:'#A44'}} width={3.0}/>
					</div>

					<div class='my-2 p-1 bg-slate-200 font-bold text-center'>Legeleistung Geflügel</div>
					<div class='flex flex-row justify-evenly'>
						<Trend title='Eier / Jahr %'  data={report.trend.years} unit='layEggs' factor={100} color={{fill:'#FEA', border:'#A94'}} width={1.5}/>
						<Trend title='Eiergewicht %'  data={report.trend.years} unit='layWeight' factor={100} color={{fill:'#FEA', border:'#A94'}} width={1.5} />
					</div>

					<div class='my-2 p-1 bg-slate-200 font-bold text-center'>Brutleistung Geflügel</div>
					<div class='flex flex-row justify-evenly'>
						<Trend title='Eingelegte Eier'  data={report.trend.years} unit='broodLayerEggs' factor={100} color={{fill:'#CFD', border:'#4A5'}} />
						<Trend title='Befruchtet %'  data={report.trend.years} unit='broodLayerFertile' scale={{min:0, max:100}} factor={100} color={{fill:'#CFD', border:'#4A5'}} />
						<Trend title='Geschlüpft %'  data={report.trend.years} unit='broodLayerHatched' scale={{min:0, max:100}} factor={100} color={{fill:'#CFD', border:'#4A5'}} />
					</div>

					<div class='my-2 p-1 bg-slate-200 font-bold text-center'>Brutleistung Tauben</div>
					<div class='flex flex-row justify-evenly'>
						<Trend title='Gemeldete Nester'  data={report.trend.years} unit='broodPigeonEggs' factor={0.5} color={{fill:'#CFD', border:'#48F'}} />
						<Trend title='Geschlüpft %'  data={report.trend.years} unit='broodPigeonHatched' scale={{min:0, max:100}} factor={100} color={{fill:'#CFD', border:'#48F'}} />
						<Trend title='Küken / Paar'  data={report.trend.years} unit='broodPigeonResult' color={{fill:'#CFD', border:'#48F'}} />
					</div>

					<div class='my-2 p-1 bg-slate-200 font-bold text-center'>Schauleistung</div>
					<div class='flex flex-row justify-evenly'>
						<Trend title='Gemeldete Tiere'  data={report.trend.years} unit='showCount' color={{fill:'#ACF', border:'#44A'}} width={1.5} />
						<Trend title='Bewertung'  data={report.trend.years} unit='showScore' scale={{min:89, max:97}} color={{fill:'#ACF', border:'#44A'}} width={1.5} />
					</div>
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

