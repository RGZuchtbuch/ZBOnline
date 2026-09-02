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

//	let { report } = $props();
	let { district, year, group, section, breed, color, chart, map, trend, table } = $props();

	const units = {
		breeders               : { id:'breeders', name:'Gemeldete Zuchten', factor:1 },

		layBreeders            : { id:'layBreeders', name:'▸ Zuchten mit Legeleistung', factor:1 },
		layEggs            : {id: 'layEggs', name: '\xA0\xA0\xA0 ▸ Legeleistung, Eier/Jahr %', factor:100 },
		layWeight          : {id: 'layWeight', name: '\xA0\xA0\xA0 ▸ Legeleistung, Eiergewicht %', factor:100 },

		broodLayerBreeders     : { id:'broodLayerBreeders', name:'▸ Geflügel: Zuchten mit Brutleistung', factor:1 },
		broodLayerEggs     : { id:'broodLayerEggs', name:'\xA0\xA0\xA0 ▸ Geflügel: Eingelegte Eier', factor:1},
		broodLayerFertile  : { id:'broodLayerFertile', name:'\xA0\xA0\xA0 ▸ Geflügel: Befruchtung %', factor:100 },
		broodLayerHatched  : { id:'broodLayerHatched', name:'\xA0\xA0\xA0 ▸ Geflügel: Schlupf %', factor:100 },

		broodPigeonBreeders    : { id:'broodPigeonBreeders', name:'▸ Tauben: Zuchten mit Brutleistung', factor:1 },
		broodPigeonEggs    : { id:'broodPigeonEggs', name:'\xA0\xA0\xA0 ▸ Tauben: Gelegte Eier', factor:1},
		broodPigeonHatched : { id:'broodPigeonHatched', name:'\xA0\xA0\xA0 ▸ Tauben: Schlupf %', factor:100 },
		broodPigeonResult  : { id:'broodPigeonResult', name:'\xA0\xA0\xA0 ▸ Tauben: Küken / Paar', factor:1 },

		showBreeders           : { id:'showBreeders', name:'▸ Zuchten mit Schauleistung', factor:1 },
		showCount          : { id:'showCount', name:'\xA0\xA0\xA0 ▸ Schauleistung, Ausgestellten Tiere', factor:1 },
		showScore          : { id:'showScore', name:'\xA0\xA0\xA0 ▸ Schauleistung, Note', factor:1 },
	}

	// for map only, should it be in Map ?
	let unitId = $state( page.url.searchParams.get( 'unit' ) || 'breeders' );
	let unit = $derived( units[ unitId ] ?? null );

	//TODO move to map ?
	function onUnitChange( event ) {
		const id = event.target.value;
		let url = new URL(page.url);
		url.searchParams.set('unit', id);
		goto(url.href);
	}

</script>

<section>
	<p class='hidden md:block px-4 my-2 text-center print:hidden'>
		Die Meldungen werden ab 2024 in diesem Programm gespeichert. Nach und nach werden auch frühere Meldungen eingegeben.<br>
		Nicht jeder Meldung enthält jeder Leistung. Leistungsdurchschnitte werder jeweils pro meldender Zucht berechnet.
	</p>

	<Filter {district} {year} {group} {section} {breed} {color} />

	<div class='flex flex-col break-after-page'>

		<!-- CHART -->
		<header class='border-header bg-header text-header'>Gesamtleistungen im {district.name} in {year}</header>
		{#if chart}
			{#key chart}
				<Chart {district} {year} data={chart} />
			{/key}
		{/if}

		<!-- MAP -->
		<div class='flex flex-col '>
			<header>Landesverbänder Leistungen in {year}</header>
			<div class='mt-4 flex flex-row justify-evenly print:hidden'>
				<Select class='' label='Leistung' value={unitId} onchange={onUnitChange}>
					{#each Object.values( units ) as unit}
						<option value={unit.id}>{unit.name}</option>
					{/each}
				</Select>
			</div>
			<div class='mt-4 text-center screen:hidden'>Leistung: {unit.name}</div>
			{#if map && unit }
				<div class='flex flex-row justify-center'>
					<!--Map report={report.map} typeId={report.args.type} /-->
					<Map title={units[ unitId ].name} data={map} {unit}/>
				</div>
			{/if}
		</div>
	</div>


	<div class='flex flex-col break-after-page'>
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
			{#if trend}
				{#key trend}
					<div class='my-2 p-1 bg-slate-200 font-bold text-center'>Gemeldete Zuchten</div>
					<div class='flex flex-row justify-evenly'>
						<!--Trend report={report.trend} typeId={report.args.type} /-->
						<Trend title='Zuchten' data={trend.years} unit='breeders' color={{fill:'#FAA', border:'#A44'}} width={1.0}/>
					</div>

					<div class='my-2 p-1 bg-slate-200 font-bold text-center'>Legeleistung Geflügel</div>
					<div class='flex flex-row flex-wrap justify-evenly'>
						<Trend title='Eier / Jahr %'  data={trend.years} unit='layEggs' factor={100} color={{fill:'#FEA', border:'#A94'}} width={1}/>
						<Trend title='Eiergewicht %'  data={trend.years} unit='layWeight' factor={100} color={{fill:'#FEA', border:'#A94'}} width={1} />
					</div>

					<div class='my-2 p-1 bg-slate-200 font-bold text-center'>Brutleistung Geflügel</div>
					<div class='flex flex-row flex-wrap justify-evenly'>
						<Trend title='Eingelegte Eier' data={trend.years} unit='broodLayerEggs' factor={1.0} color={{fill:'#CFD', border:'#4A5'}} />
						<Trend title='Befruchtet %'    data={trend.years} unit='broodLayerFertile' scale={{min:0, max:100}} factor={100} color={{fill:'#CFD', border:'#4A5'}} />
						<Trend title='Geschlüpft %'    data={trend.years} unit='broodLayerHatched' scale={{min:0, max:100}} factor={100} color={{fill:'#CFD', border:'#4A5'}} />
					</div>

					<div class='my-2 p-1 bg-slate-200 font-bold text-center'>Brutleistung Tauben</div>
					<div class='flex flex-row flex-wrap justify-evenly'>
						<Trend title='Gelegte Eier' data={trend.years} unit='broodPigeonEggs' factor={1.0} color={{fill:'#CFD', border:'#48F'}} />
						<Trend title='Geschlüpft %' data={trend.years} unit='broodPigeonHatched' scale={{min:0, max:100}} factor={100} color={{fill:'#CFD', border:'#48F'}} />
						<Trend title='Küken / Paar' data={trend.years} unit='broodPigeonResult' color={{fill:'#CFD', border:'#48F'}} />
					</div>

					<div class='my-2 p-1 bg-slate-200 font-bold text-center'>Schauleistung</div>
					<div class='flex flex-row flex-wrap justify-evenly'>
						<Trend title='Gemeldete Tiere' data={trend.years} unit='showCount' color={{fill:'#ACF', border:'#44A'}} width={1.0} />
						<Trend title='Bewertung'       data={trend.years} unit='showScore' scale={{min:89, max:97}} color={{fill:'#ACF', border:'#44A'}} width={1.0} />
					</div>
				{/key}
			{/if}

		</div>

	</div>


	<hr class='my-4 print:hidden'/>

	<!-- TABLE -->
	<div class='flex flex-col break-after-page'>
		<header class=''>Leistungsdaten im {district.name} für {year}</header>
		{#if table && district && year}
			<div class='flex flex-col py-4'>
				{#key table} <!-- needed to trigger -->
					<Table data={table} {district} {year} />
				{/key}
			</div>
		{/if}
	</div>
</section>

<style>
	header {
		@apply px-4 py-2 border-header bg-header text-header text-center text-xl font-bold screen:sticky screen:top-1;
	}
    summary {
        @apply px-4 py-2 bg-teal-200 text-xl font-bold sticky top-0 text-center;
    }
</style>

