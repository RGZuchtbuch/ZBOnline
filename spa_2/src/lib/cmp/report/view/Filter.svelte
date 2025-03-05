<script>
	import { goto } from '$app/navigation';
	import { page } from '$app/state';
	import Select from '$lib/cmp/form/input/Select.svelte';
	import Form from '$lib/cmp/form/Form.svelte';

//	let { district, year, group, section, breed, color, type, federation, standard } = $props();
	let { data } = $props();

	const url = new URL( page.url );

	let districtId = $state( +page.url.searchParams.get( 'district' ) );
	let year       = $state( +page.url.searchParams.get( 'year' ) );
	let group      = $state(  page.url.searchParams.get( 'group' ) );
	let sectionId  = $state( +page.url.searchParams.get( 'section' ) ); // becomes 0 when null
	let breedId    = $state( +page.url.searchParams.get( 'breed' ) );
	let colorId    = $state( +page.url.searchParams.get( 'color' ) );

	let district = $state( data.federation.districts[ districtId ] );
	let section  = $state( data.standard.rootSections.find( item => item.id === sectionId ) );
	let breed    = $state( data.standard.breeds[ breedId ] );
	let color    = $state( data.standard.colors[ colorId ] );

	console.log( 'Dis', districtId, sectionId );

	const groups = ['I','II','III'];
	let years = [];
	let nextYear = new Date().getFullYear()+1;
	for( let year=nextYear; year>=1980; year-- ) { // desc from next year down to 1980. Could be refined to earliest data ?.
		years.push( year );
	}

	function onDistrictChange( event ) {
		console.log( 'ETV',  );
		districtId = +event.target.value
		data.url.searchParams.set( 'district', districtId );
		goto( data.url );
	}
	function onYearChange( event ) {
		year = +event.target.value;
		data.url.searchParams.set( 'year', year );
		goto( data.url );
	}

	function onGroupChange() {
		if( group ) {
			data.url.searchParams.set('group', year);
		} else {
			data.url.searchParams.delete('group');
		}
		goto( data.url );
	}

	function onSectionChange( event ) {
		sectionId = +event.target.value
		section = data.standard.rootSections.find( item => item.id === sectionId );
		breed = null;
		color = null;

		if (section) {
			data.url.searchParams.set('section', sectionId);
			console.log('SectionChange', section, sectionId );
		} else {
			data.url.searchParams.delete( 'section' );
		}
		data.url.searchParams.delete( 'breed' );
		data.url.searchParams.delete( 'color' );
		console.log( 'URL', data.url );
		goto( data.url );
	}
	function onBreedChange( event ) {
		breedId = +event.target.value;
		breed = data.standard.breeds[ breedId ];
		color = null;
		if( breedId ) {
			data.url.searchParams.set( 'breed', breedId );
		} else {
			data.url.searchParams.delete( 'breed' );
		}
		data.url.searchParams.delete( 'color' );
		goto( data.url );
	}
	function onColorChange( event ) {
		colorId = +event.target.value;
		color = data.standard.colors[ colorId ];
		if( color ) {
			data.url.searchParams.set( 'color', colorId );
		} else {
			data.url.searchParams.delete( 'color' );
		}
		goto( data.url );
	}

	$inspect('R', districtId, year, group );

</script>

<Form>
<section class='flex flex-row gap-x-2 p-4' >
	<Select class='' label='Verband' value={1} onchange={onDistrictChange}>
		<option value={data.federation.root.id}>{data.federation.root.name}</option>/
		{#each data.federation.root.children as district}
			<option value={district.id}>{district.name}</option>/
			{#each district.children as district}
				<option value={district.id}>&nbsp; &nbsp; {district.name}</option>/
			{/each}
		{/each}
	</Select>
	<Select class='' label='Jahr' value={year} onchange={onYearChange}>
		<option value={null}>?</option>/
		{#each years as year}
			<option value={year}>{year}</option>/
		{/each}
	</Select>
	<Select class='' label='ZB Gruppe' value={group} onchange={onGroupChange}>
		<option value={null} title='Alle Gruppen'>*</option>/
		{#each groups as group}
			<option value={group}>{group}</option>/
		{/each}
	</Select>
</section>


<section class='flex flex-row gap-x-2 p-4' >
	<Select class='w-56' label='Sparte' value={sectionId} onchange={onSectionChange}>
		<option value={0}>*</option>
		{#each data.standard.rootSections as section}
			<option value={section.id}>&nbsp; {section.name}</option>/
		{/each}
	</Select>

	<Select class='w-96' label='Rasse' value={breedId} onchange={onBreedChange}>
		<option value={0}>*</option>/
		{#if section}
			{#each section.breeds as breed}
				<option value={breed.id}>{breed.name}</option>/
			{/each}
		{/if}
	</Select>

	<Select class='w-80' label='Farbenschlag' value={colorId} onchange={onColorChange}>
		<option value={0}>*</option>/
		{#if breed}
			{#each breed.colors as color}
				<option value={color.id}>{color.name}</option>/
			{/each}
		{/if}
	</Select>

</section>
</Form>