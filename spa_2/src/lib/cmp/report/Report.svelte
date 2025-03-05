<script>
	import {fade} from 'svelte/transition';
	import {page} from '$app/state';
	import Filter from './view/Filter.svelte';
	import LayBar from './view/LayBar.svelte';
	import BroodBarLayers from './view/BroodBarLayers.svelte';
	import BroodBarPigeons from './view/BroodBarPigeons.svelte';

	let { data } = $props();

	// let district = $state( null );
	// let year = $state( null );
	// let group = $state( null );
	// let section = $state( null );
	// let breed = $state( null );
	// let color = $state( null );
	// let type = $state( null );

	// $effect( () => {
	// 	district = data.federation.districts[ +page.url.searchParams.get( 'district' ) ] || data.federation.root;
	// 	year     = +page.url.searchParams.get( 'year' ) || new Date().getFullYear()-1;
	// 	group    = page.url.searchParams.get( 'group' ) || null;
	// 	//section  = data.standard.rootSections.find( ( item ) => item.id === +page.url.searchParams.get( 'section' ) ) || null;
	// 	breed    = data.standard.breeds[ +page.url.searchParams.get( 'breed' ) ] || null;
	// 	color    = data.standard.colors[ +page.url.searchParams.get( 'color' ) ] || null;
	// 	type     = +page.url.searchParams.get( 'type' ) || null;
	// 	console.log( 'query changed', district );
	// });


</script>


<h3 class='header'>Zuchtleistungen</h3>

<!--Filter class='print:hidden' {...data} bind:district={district} bind:year={year} bind:group={group} bind:section={section} bind:breed={breed} bind:color={color} bind:type={type}/-->
<Filter class='print:hidden' {data}/>

<div class='flex flex-row border rounded mx-4 p-4'>
	<LayBar class='' report={data.reports.chart.report} />
	<BroodBarLayers report={data.reports.chart.report} />
	<BroodBarPigeons report={data.reports.chart.report} />
</div>




