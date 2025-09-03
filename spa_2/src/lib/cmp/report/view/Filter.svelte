<script>
	import { goto } from '$app/navigation';
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Select from '$lib/cmp/form/input/Select.svelte';
	import Form from '$lib/cmp/form/Form.svelte';
	import {onMount} from 'svelte';

	let { report } = $props();

	let district = $state( ctx.federation.districts[ report.args.district ] )
	let year     = $state( report.args.year );
	let group    = $state( report.args.group );
	let section  = $state( ctx.standard.rootSections.find( item => item.id === report.args.section ) );
	let breed    = $state( ctx.standard.breeds[ report.args.breed ] );
	let color    = $state( ctx.standard.colors[ report.args.color ] );

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
		section = ctx.standard.rootSections.find( item => item.id === sectionId );
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

</script>

{#if ctx.federation && ctx.standard && report.args }
	<Form>
		<section class='flex flex-row gap-x-2 p-4' >
			<Select class='' label='Verband' value={report.args.district} onchange={onDistrictChange}>
				<option value={ctx.federation.id}>{ctx.federation.name}</option>
				{#each ctx.federation.children as district}
					<option value={district.id}>{district.name}</option>
					{#each district.children as district}
						<option value={district.id}>&nbsp; &nbsp; {district.name}</option>
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


		<section class='flex flex-row gap-x-2 p-4' >
			<Select class='w-56' label='Sparte' value={report.args.section} onchange={onSectionChange}>
				<option value={undefined}>*</option>
				{#each ctx.standard.rootSections as section}
					<option value={section.id}>{section.name}</option>
				{/each}
			</Select>

			<Select class='w-96' label='Rasse' value={report.args.breed} onchange={onBreedChange}>
				<option value={undefined}>*</option>
				{#if section}
					{#each section.breeds as breed}
						<option value={breed.id}>{breed.name}</option>
					{/each}
				{/if}
			</Select>

			<Select class='w-80' label='Farbenschlag' value={report.args.color} onchange={onColorChange}>
				<option value={undefined}>*</option>
				{#if breed}
					{#each breed.colors as color}
						<option value={color.id}>{color.name}</option>
					{/each}
				{/if}
			</Select>

		</section>
	</Form>
{/if}