<script>
	import { goto } from '$app/navigation';
	import { page } from '$app/state';
	import store from '$lib/js/store.svelte.js';

	import Select from '$lib/cmp/form/input/Select.svelte';
	import Form from '$lib/cmp/form/Form.svelte';

	const query = $derived( { // get value or default/null
		district : +page.url.searchParams.get('district') || 1,
		year     : +page.url.searchParams.get('year') || new Date().getFullYear() - 1,
		group    :  page.url.searchParams.get('group'),
		section  : +page.url.searchParams.get('section') || null,
		breed    : +page.url.searchParams.get('breed') || null,
		color    : +page.url.searchParams.get('color') || null,
	} );


	let district = $state( store.federation.districts[ query.district ] );
	let year     = $state( query.year );
	let group    = $state( query.group );
	let section  = $state( store.standard.rootSections.find( item => item.id === query.section ) );
	let breed    = $state( store.standard.breeds[ query.breed ] );
	let color    = $state( store.standard.colors[ query.color ] );

	const groups = ['I','II','III'];
	let years = [];
	let nextYear = new Date().getFullYear()+1;
	for( let year=nextYear; year>=1980; year-- ) { // desc from next year down to 1980. Could be refined to earliest data ?.
		years.push( year );
	}

	function onDistrictChange( event ) {
		district = store.federation.districts[ +event.target.value ]
		const url =new URL( page.url ); // for query changes
		url.searchParams.set( 'district', district.id );
		goto( url.href );
	}
	function onYearChange( event ) {
		year = +event.target.value;
		const url =new URL( page.url ); // for query changes
		url.searchParams.set( 'year', year );
		goto( url.href );
	}
	function onGroupChange( event ) {
		group = event.target.value;
		const url =new URL( page.url ); // for query changes
		if( group ) {
			url.searchParams.set('group', group);
		} else {
			url.searchParams.delete('group');
		}
		goto( url.href );
	}
	function onSectionChange( event ) {
		section = store.standard.rootSections.find( item => item.id === +event.target.value );
		breed = null;
		color = null;
		const url =new URL( page.url ); // for query changes
		if (section) {
			url.searchParams.set( 'section', section.id );
		} else {
			url.searchParams.delete( 'section' );
		}
		url.searchParams.delete( 'breed' );
		url.searchParams.delete( 'color' );
		goto( url.href );
	}
	function onBreedChange( event ) {
		breed = store.standard.breeds[ +event.target.value ];
		color = null;
		const url =new URL( page.url ); // for query changes
		if( breed ) {
			url.searchParams.set( 'breed', breed.id );
		} else {
			url.searchParams.delete( 'breed' );
		}
		url.searchParams.delete( 'color' );
		goto( url.href );
	}
	function onColorChange( event ) {
		color = store.standard.colors[ +event.target.value ];
		const url =new URL( page.url ); // for query changes
		if( color ) {
			url.searchParams.set( 'color', color.id );
		} else {
			url.searchParams.delete( 'color' );
		}
		goto( url.href );
	}


</script>

<Form>
<section class='flex flex-row gap-x-2 p-4' >
	<Select class='' label='Verband' value={query.district} onchange={onDistrictChange}>
		<option value={store.federation.id}>{store.federation.name}</option>/
		{#each store.federation.children as district}
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
		{#each store.standard.rootSections as section}
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