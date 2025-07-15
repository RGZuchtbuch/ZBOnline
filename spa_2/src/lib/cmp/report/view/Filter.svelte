<script>
	import { goto } from '$app/navigation';
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Select from '$lib/cmp/form/input/Select.svelte';
	import Form from '$lib/cmp/form/Form.svelte';
	import {onMount} from 'svelte';

	let { args, federation, standard } = $props();

	// const query = $derived( { // get value or default/null
	// 	district : +page.url.searchParams.get('district') || 1,
	// 	year     : +page.url.searchParams.get('year') || new Date().getFullYear() - 1,
	// 	group    :  page.url.searchParams.get('group'),
	// 	section  : +page.url.searchParams.get('section') || null,
	// 	breed    : +page.url.searchParams.get('breed') || null,
	// 	color    : +page.url.searchParams.get('color') || null,
	// } );


	let district = $state( federation.districts[ args.district ] )
	let year     = $state( args.year );
	let group    = $state( args.group );
	let section  = $state( standard.rootSections.find( item => item.id === args.section ) );
	let breed    = $state( standard.breeds[ args.breed ] );
	let color    = $state( standard.colors[ args.color ] );

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
		//district = federation.districts[ districtId ]
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
		section = standard.rootSections.find( item => item.id === sectionId );
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
		breed = standard.breeds[ breedId ];
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
		color = standard.colors[ +event.target.value ];
		const url =new URL( page.url ); // for query changes
		if( color ) {
			url.searchParams.set( 'color', colorId );
		} else {
			url.searchParams.delete( 'color' );
		}
		goto( url.href );
	}

</script>

{#if federation && standard }
	<Form>
		<section class='flex flex-row gap-x-2 p-4' >
			<Select class='' label='Verband' value={args.district} onchange={onDistrictChange}>
				<option value={federation.id}>{federation.name}</option>/
				{#each federation.children as district}
					<option value={district.id}>{district.name}</option>/
					{#each district.children as district}
						<option value={district.id}>&nbsp; &nbsp; {district.name}</option>/
					{/each}
				{/each}
			</Select>

			<Select class='' label='Jahr' value={args.year} onchange={onYearChange}>
				{#each years as year}
					<option value={year}>{year}</option>/
				{/each}
			</Select>

			<Select class='' label='ZB Gruppe' value={args.group} onchange={onGroupChange}>
				<option value={undefined} title='Alle Gruppen'>*</option>/
				{#each groups as group}
					<option value={group}>{group}</option>/
				{/each}
			</Select>
		</section>


		<section class='flex flex-row gap-x-2 p-4' >
			<Select class='w-56' label='Sparte' value={args.section} onchange={onSectionChange}>
				<option value={undefined}>*</option>
				{#each standard.rootSections as section}
					<option value={section.id}>{section.name}</option>
				{/each}
			</Select>

			<Select class='w-96' label='Rasse' value={args.breed} onchange={onBreedChange}>
				<option value={undefined}>*</option>
				{#if section}
					{#each section.breeds as breed}
						<option value={breed.id}>{breed.name}</option>
					{/each}
				{/if}
			</Select>

			<Select class='w-80' label='Farbenschlag' value={args.color} onchange={onColorChange}>
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