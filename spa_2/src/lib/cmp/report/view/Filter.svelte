<script>
	import { goto } from '$app/navigation';
	import { onMount } from 'svelte';
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import { activeYear, getArgNum, getArgStr } from '$lib/js/tools.js';
	import model from '$lib/js/model.js';

	import Select from '$lib/cmp/form/input/Select.svelte';
	import Form from '$lib/cmp/form/Form.svelte';



	let { report } = $props();

	let district = $derived( ctx.federation.districts[ getArgNum( page.url, 'district', 1 ) ] ); //report.args.district ] )
	let year     = $derived( getArgNum( page.url, 'year', activeYear() ) ); //report.args.year );
	let group    = $derived( getArgStr( page.url, 'group', null ) );
//	let section  = $derived( ctx.standard.rootSections.find( item => item.id === report.args.section ) );
	let section  = $derived( ctx.standard.sections[ getArgNum( page.url, 'section', null ) ] );
//	let section  = $derived( ctx.standard.sections[ report.args.section ] );
//	let breed    = $state( ctx.standard.breeds[ report.args.breed ] );
	let breed    = $derived( ctx.standard.breeds[ getArgNum( page.url, 'breed', null ) ] );
	let color    = $derived( ctx.standard.colors[ getArgNum( page.url, 'color', null ) ] );

	const groups = ['I','II','III'];
	let years = [];
	let nextYear = new Date().getFullYear()+1;
	for( let year=nextYear; year>=1980; year-- ) { // desc from next year down to 1980. Could be refined to earliest data ?.
		years.push( year );
	}

	onMount( async () => {
	})

	function onDistrictChange( event ) {
		let districtId = +event.target.value
		const url =new URL( page.url ); // for query changes
		url.searchParams.set( 'district', districtId );
		goto( url.href );
	}
	function onYearChange( event ) {
		let year = +event.target.value;
		const url =new URL( page.url ); // for query changes
		url.searchParams.set( 'year', year );
		goto( url.href );
	}
	function onGroupChange( event ) {
		let group = event.target.value;
		const url =new URL( page.url ); // for query changes
		if( group ) {
			url.searchParams.set('group', group );
		} else {
			url.searchParams.delete('group');
		}
		goto( url.href );
	}
	function onSectionChange( event ) {
		let sectionId = +event.target.value;
		console.log( 'Section', sectionId );
		//section = ctx.standard.rootSections.find( item => item.id === sectionId );
		section = ctx.standard.sections[ sectionId ];
		breed = null;
		//color = null;
		const url =new URL( page.url ); // for query changes
		if( section ) {
			url.searchParams.set( 'section', sectionId );
		} else {
			url.searchParams.delete( 'section' );
		}
		url.searchParams.delete( 'breed' );
		url.searchParams.delete( 'color' );
		goto( url.href );
	}
	function onBreedChange( event ) {
		let breedId = +event.target.value;
		breed = ctx.standard.breeds[ breedId ];
		color = null;
		const url =new URL( page.url ); // for query changes
		if( breed ) {
			url.searchParams.set( 'breed', breedId );
		} else {
			url.searchParams.delete( 'breed' );
		}
		url.searchParams.delete( 'color' );
		goto( url.href );
	}
	function onColorChange( event ) {
		let colorId = +event.target.value;
		color = ctx.standard.colors[ +event.target.value ];
		const url =new URL( page.url ); // for query changes
		if( color ) {
			url.searchParams.set( 'color', colorId );
		} else {
			url.searchParams.delete( 'color' );
		}
		goto( url.href );
	}

	//const testSections = [ ctx.standard.sections[2], ctx.standard.sections[13], ctx.standard.sections[17] ];
	// ctx.standard.rootSections
	const testSections = ctx.standard.root;
</script>

{#if ctx.federation && ctx.standard && report.args }
	<Form>
		<h3 class='text-center print:hidden'>Filter</h3>

		<p class='my-2 print:hidden'>
			Die Meldungen werden ab 2024 in diesem Programm gespeichert. Nach und nach werden auch frühere Meldungen eingegeben.<br>
			Nicht jeder Meldung enthält Jeder Leistung. Deshalb kann die Zahl der gemeldete Zuchten pro Leistung unterschiedlich sein.
		</p>

		<section class='flex flex-row gap-x-2 px-4 print:hidden' >
			<Select class='' label='Verband' value={report.args.district} onchange={onDistrictChange}>
				<option value={ctx.federation.id}>{ctx.federation.name}</option>
				{#each ctx.federation.children as district}
					<option value={district.id}>▸&nbsp;{district.name}</option>
					{#each district.children as district}
						<option value={district.id}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;◦&nbsp;&nbsp;{district.name}</option>
					{/each}
				{/each}
			</Select>

			<Select class='' label='Jahr' value={report.args.year} onchange={onYearChange}>
				{#each years as year}
					<option value={year}>{year}</option>
				{/each}
			</Select>

			<Select class='' label='ZB Gruppe' value={report.args.group} onchange={onGroupChange}>
				<option value={undefined} title='Alle Gruppen'>*</option>
				{#each groups as group}
					<option value={group}>{group}</option>
				{/each}
			</Select>
		</section>


		<section class='flex flex-row gap-x-2 px-4 print:hidden' >
			<Select class='' label='Sparte' value={report.args.section} onchange={onSectionChange}>
				<option value={undefined}>*</option>
				{#each ctx.standard.root.children as section}
					<option value={section.id}>▸&nbsp;{section.name}</option>
					{#each section.children as subSection}
						<option value={subSection.id}>&nbsp;&nbsp;&nbsp;&nbsp;◦&nbsp;&nbsp;{subSection.name}</option>
					{/each}
				{/each}
			</Select>

			<Select class='min-w-64' label='Rasse' value={report.args.breed} onchange={onBreedChange}>
				<option value={undefined}>*</option>
				{#if section}
					{#each section.breeds as breed}
						<option value={breed.id}>{breed.name}</option>
					{/each}
				{/if}
			</Select>

			<Select class='min-w-64' label='Farbenschlag' value={report.args.color} onchange={onColorChange}>
				<option value={undefined}>*</option>
				{#if breed}
					{#each breed.colors as color}
						<option value={color.id}>{color.name}</option>
					{/each}
				{/if}
			</Select>

		</section>

		<section class='screen:hidden'>
			<h1 class='flex flex-row mx-16 justify-center gap-x-8'>
				<span>{ year }</span>
				<span>{ district.name }</span>
				<span>{ group ? 'Zuchtbuchgruppe ${group}' : 'Zuchtbuchgruppen (I,II,III)' }</span>
			</h1>
			<h2 class='flex flex-row mx-16 justify-center gap-x-8'>
				<span>{section ? section.name : 'Alle Sparten'}</span>
				<span>, {breed   ? breed.name   : 'Alle Rassen'}</span>
				<span>, {color   ? color.name   : 'Alle Farbenschläge'}</span>
			</h2>
		</section>
	</Form>
{/if}