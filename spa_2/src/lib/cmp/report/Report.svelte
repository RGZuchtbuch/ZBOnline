<script>
	import {fade} from 'svelte/transition';
	import store, { reports } from '$lib/js/store.svelte.js';
	import Filter from './view/Filter.svelte';
	import LayBar from './view/LayBar.svelte';
	import BroodBarLayers from './view/BroodBarLayers.svelte';
	import BroodBarPigeons from './view/BroodBarPigeons.svelte';

	let { query } = $props();
	console.log( 'Report query', query );

	const chart = reports.chart;
	const map = reports.map;
	const trend = reports.trend;


</script>


<h3 class='header'>Zuchtleistungen</h3>

<!--Filter class='print:hidden' {...data} bind:district={district} bind:year={year} bind:group={group} bind:section={section} bind:breed={breed} bind:color={color} bind:type={type}/-->
<Filter class='print:hidden' />

{#if $chart}
	<div class='flex flex-row border rounded mx-4 p-4'>
		<LayBar class='' report={$chart.report} />
		<BroodBarLayers report={$chart.report} />
		<BroodBarPigeons report={$chart.report} />
	</div>
{/if}




