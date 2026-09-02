<script>
	import { goto } from '$app/navigation';
	import { onMount } from 'svelte';
	import { page } from '$app/state';
	import { ctx, groups } from '$lib/js/store.svelte.js';

	import Select from '$lib/cmp/form/input/Select.svelte';
	import Form from '$lib/cmp/form/Form.svelte';



	let { district, year, group, section, breed, color } = $props();

// 	let district = $derived( ctx.federation.districts[ getArgNum( page.url, 'district', 1 ) ] ); //report.args.district ] )
// 	let year     = $derived( getArgNum( page.url, 'year', activeYear() ) ); //report.args.year );
// 	let group    = $derived( getArgStr( page.url, 'group', null ) );
// //	let section  = $derived( ctx.standard.rootSections.find( item => item.id === report.args.section ) );
// 	let section  = $derived( ctx.standard.sections[ getArgNum( page.url, 'section', null ) ] );
// //	let section  = $derived( ctx.standard.sections[ report.args.section ] );
// //	let breed    = $state( ctx.standard.breeds[ report.args.breed ] );
// 	let breed    = $derived( ctx.standard.breeds[ getArgNum( page.url, 'breed', null ) ] );
// 	let color    = $derived( ctx.standard.colors[ getArgNum( page.url, 'color', null ) ] );

	//const groups = ['I','II','III']; from store
	let years = [];
	let nextYear = CURRENT_INPUT_YEAR+1;
	for( let year=nextYear; year>=START_YEAR; year-- ) { // desc from next year down to 1980. Could be refined to earliest data ?.
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
		let id = +event.target.value;
		console.log( 'Section', id );
		//section = ctx.standard.rootSections.find( item => item.id === sectionId );
		//section = ctx.standard.sections[ sectionId ];
		//breed = null;
		//color = null;
		const url =new URL( page.url ); // for query changes
		if( id ) {
			url.searchParams.set( 'section', id );
		} else {
			url.searchParams.delete( 'section' );
		}
		url.searchParams.delete( 'breed' );
		url.searchParams.delete( 'color' );
		goto( url.href );
	}
	function onBreedChange( event ) {
		let id = +event.target.value;
		//breed = ctx.standard.breeds[ breedId ];
		//color = null;
		const url =new URL( page.url ); // for query changes
		if( id ) {
			url.searchParams.set( 'breed', id );
		} else {
			url.searchParams.delete( 'breed' );
		}
		url.searchParams.delete( 'color' );
		goto( url.href );
	}
	function onColorChange( event ) {
		let id = +event.target.value;
		//color = ctx.standard.colors[ +event.target.value ];
		const url =new URL( page.url ); // for query changes
		if( id ) {
			url.searchParams.set( 'color', id );
		} else {
			url.searchParams.delete( 'color' );
		}
		goto( url.href );
	}

	//const testSections = [ ctx.standard.sections[2], ctx.standard.sections[13], ctx.standard.sections[17] ];
	// ctx.standard.rootSections
	const testSections = ctx.standard.root;
</script>

{#if ctx.federation && ctx.standard }
	<Form>
		<h3 class='text-center print:hidden'>Filter</h3>



		<section class='flex flex-row flex-wrap gap-x-2 px-4 print:hidden' >
			<Select class='' label='Verband' value={district.id} onchange={onDistrictChange}>
				<option value={ctx.federation.id}>{ctx.federation.name}</option>
				{#each ctx.federation.children as child}
					<option value={child.id}>▸&nbsp;{child.name}</option>
					{#each child.children as child}
						<option value={child.id}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;◦&nbsp;&nbsp;{child.name}</option>
					{/each}
				{/each}
			</Select>

			<Select class='' label='Jahr' value={year} onchange={onYearChange}>
				{#each years as year}
					<option value={year}>{year}</option>
				{/each}
			</Select>

			<Select class='' label='ZB Gruppe' value={group} onchange={onGroupChange}>
				<option value={null} title='Alle Gruppen'>*</option>
				{#each groups as group}
					<option value={group}>{group}</option>
				{/each}
			</Select>
		</section>


		<section class='flex flex-row flex-wrap gap-x-2 px-4 print:hidden' >
			<Select class='' label='Sparte' value={section?section.id:null} onchange={onSectionChange}>
				<option value={null}>*</option>
				{#each ctx.standard.root.children as child}
					<option value={child.id}>▸&nbsp;{child.name}</option>
					{#each child.children as child}
						<option value={child.id}>&nbsp;&nbsp;&nbsp;&nbsp;◦&nbsp;&nbsp;{child.name}</option>
					{/each}
				{/each}
			</Select>

			<Select class='min-w-64' label='Rasse' value={breed?breed.id:null} onchange={onBreedChange}>
				<option value={null}>*</option>
				{#if section}
					{#each section.breeds as breed}
						<option value={breed.id}>{breed.name}</option>
					{/each}
				{/if}
			</Select>

			<Select class='min-w-64' label='Farbenschlag' value={color?color.id:null} onchange={onColorChange}>
				<option value={null}>*</option>
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