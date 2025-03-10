<script>
	import { goto } from '$app/navigation';
	import { page } from '$app/state';
	import store, { federation, standard } from '$lib/js/store.svelte.js';

	import Select from '$lib/cmp/form/input/Select.svelte';
	import Form from '$lib/cmp/form/Form.svelte';
	import api from '$lib/js/api.js';

	//let props = $props();

//	let { district, year, group, section, breed, color, type, federation, standard } = $props();
//	let { data } = $props();

	const query = $derived( {
		district : +page.url.searchParams.get('district') || 1,
		year     : +page.url.searchParams.get('year') || new Date().getFullYear() - 1,
		group    :  page.url.searchParams.get('group'),
		section  :  page.url.searchParams.get('section'),
		breed    :  page.url.searchParams.get('breed'),
		color    :  page.url.searchParams.get('color'),
	} );


	let district = $state( $federation.districts[ query.district ] );
	let year     = $state( query.year );
	let group    = $state( query.group );
	let section  = $state( $standard.rootSections.find( item => item.id === query.section ) );
	let breed    = $state( $standard.breeds[ query.breed ] );
	let color    = $state( $standard.colors[ query.color ] );

	console.log( 'Filter query', query );

	const groups = ['I','II','III'];
	let years = [];
	let nextYear = new Date().getFullYear()+1;
	for( let year=nextYear; year>=1980; year-- ) { // desc from next year down to 1980. Could be refined to earliest data ?.
		years.push( year );
	}

	const url =new URL( page.url ); // for query changes


	function onDistrictChange( event ) {
		console.log( 'ETV',  );
		district = $federation.districts[ +event.target.value ]
		url.searchParams.set( 'district', district.id );
		goto( url );
	}
	function onYearChange( event ) {
		year = +event.target.value;
		url.searchParams.set( 'year', year );
		goto( url );
	}
	function onGroupChange( event ) {
		group = event.target.value;
		if( group ) {
			url.searchParams.set('group', group);
		} else {
			url.searchParams.delete('group');
		}
		goto( url );
	}
	function onSectionChange( event ) {
		section = $standard.rootSections.find( item => item.id === +event.target.value );
		breed = null;
		color = null;

		if (section) {
			url.searchParams.set( 'section', section.id );
		} else {
			url.searchParams.delete( 'section' );
		}
		url.searchParams.delete( 'breed' );
		url.searchParams.delete( 'color' );
		goto( url );
	}
	function onBreedChange( event ) {
		breed = $standard.breeds[ +event.target.value ];
		color = null;
		if( breed ) {
			url.searchParams.set( 'breed', breed.id );
		} else {
			url.searchParams.delete( 'breed' );
		}
		url.searchParams.delete( 'color' );
		goto( url );
	}
	function onColorChange( event ) {
		color = $standard.colors[ +event.target.value ];
		if( color ) {
			url.searchParams.set( 'color', color.id );
		} else {
			url.searchParams.delete( 'color' );
		}
		goto( url );
	}

	async function load( query ) {
		// should get chart, map, trend and table data


		console.log("Loading Reports", query );

		const reducedQuery = {};
		for( let key in query ) {
			if( query[ key ] ) reducedQuery[ key ] = query[ key ];
		}

		console.log( 'RedQ', reducedQuery );

		if( reducedQuery && reducedQuery.district && reducedQuery.year ) {
			const chartPromise = getPromise('chart', reducedQuery );
			const mapPromise   = getPromise('map',   reducedQuery );
			const trendPromise = getPromise('trend', reducedQuery );
			//const tablePromise = getPromise('table', query);

//		const responses = await Promise.all([ chartPromise, mapPromise, trendPromise, tablePromise ])
			const responses = await Promise.all([ chartPromise, mapPromise, trendPromise ])

			store.reports.chart.update( () => responses[0] );
			store.reports.map.update(   () => responses[1] );
			store.reports.trend.update( () => responses[2] );
			//store.reports.table.update( () => responses[3] );
		}
	}

	async function getPromise( target, query ) {
		query.target=target;
		return api.report.get( query );
	}

	$effect( () => {
		load( query );
	})

</script>

<Form>
<section class='flex flex-row gap-x-2 p-4' >
	<Select class='' label='Verband' value={query.district} onchange={onDistrictChange}>
		<option value={$federation.root.id}>{$federation.root.name}</option>/
		{#each $federation.root.children as district}
			<option value={district.id}>{district.name}</option>/
			{#each district.children as district}
				<option value={district.id}>&nbsp; &nbsp; {district.name}</option>/
			{/each}
		{/each}
	</Select>
	<Select class='' label='Jahr' value={query.year} onchange={onYearChange}>
		<option value={null}>?</option>/
		{#each years as year}
			<option value={year}>{year}</option>/
		{/each}
	</Select>
	<Select class='' label='ZB Gruppe' value={query.group} onchange={onGroupChange}>
		<option value={null} title='Alle Gruppen'>*</option>/
		{#each groups as group}
			<option value={group}>{group}</option>/
		{/each}
	</Select>
</section>


<section class='flex flex-row gap-x-2 p-4' >
	<Select class='w-56' label='Sparte' value={section?section.id:null} onchange={onSectionChange}>
		<option value={null}>*</option>
		{#each $standard.rootSections as section}
			<option value={section.id}>&nbsp; {section.name}</option>/
		{/each}
	</Select>

	<Select class='w-96' label='Rasse' value={breed?breed.id:null} onchange={onBreedChange}>
		<option value={null}>*</option>/
		{#if section}
			{#each section.breeds as breed}
				<option value={breed.id}>{breed.name}</option>/
			{/each}
		{/if}
	</Select>

	<Select class='w-80' label='Farbenschlag' value={color?color.id:null} onchange={onColorChange}>
		<option value={null}>*</option>/
		{#if breed}
			{#each breed.colors as color}
				<option value={color.id}>{color.name}</option>/
			{/each}
		{/if}
	</Select>

</section>
</Form>