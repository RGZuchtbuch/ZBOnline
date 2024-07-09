<script>
	import validator from '../../../js/validator.js';
	import { standard } from '../../../js/store.js';
	import Form from '../../common/form/Form.svelte';
	import Select from '../../common/form/input/Select.svelte';
	import TextInput from '../../common/form/input/TextInput.svelte';
	import Submit from '../../common/form/Submit.svelte';

	export let results;
	export let districtId;
	export let year;

	let section = null;
	let breeds = [];
	let breed = null;
	let aocColor = null;

	const sections = [ // for selects
		{id: 3, name: 'Groß & Wassergeflügel'},
		{id: 11, name: 'Hühner (Groß)'}, { id: 12, name: 'Zwerghühner' }, {id: 13, name: 'Wachteln'},
		{id: 5, name: 'Tauben'},
		{id: 6, name: 'Ziergeflügel'},
	];


	const validate = {
		id:     (v) => validator(v).notNull().isValid(),
		name:   (v) => validator(v).string().length( 2, 64 ).isValid(),
	}

	function findSection( id, section ) {
		if( section.id === id ) return section;
		for( const childSection of section.children ) {
			const found = findSection( id, childSection );
			if( found ) return found;
		}
		return null; // nothing found
	}
	function collectBreeds( breeds, section ) {
		for( const breed of section.breeds ) breeds.push( breed );
		for( let childSection of section.children ) {
			collectBreeds( breeds, childSection );
		}
	}

	function onSection( event ) {
		console.log('Standard', $standard);
		breeds = [];
		aocColor = null;
		let selectedSection = findSection( section.id, $standard); // start at geflügel
		if( selectedSection ) {
			console.log( selectedSection );
			collectBreeds( breeds, selectedSection ); // in section and subs
			breeds.sort( (a, b) => a.name < b.name ? -1 : a.name > b.name ? 1 : 0 );

			console.log('Bb', breeds );
		}
	}

	function onSubmit( event ) {
		console.log('Submit' );
		console.log( 'Results A', results );
		const name = 'AOC: '+aocColor;
		const aoc = {
			pairId:null, districtId:districtId, year:year, group:'I',
			sectionId:section.id, breedId:breed.id, breedName:breed.name, colorId:null, aocColor:name, colorName:name, // name for ResultInput
			breeders:null, pairs:null, layDames:null, layEggs:null, layWeight:null,
			broodEggs:null, broodFertile:null, broodHatched:null,
			showCount:null, showScore:null
		}
		results = [aoc, ...results];
		console.log( 'Results B', results );
	}


</script>

<Form on:submit={onSubmit} autoSave={false}>
	<h2 class='text-center'>AOC Klasse, neuer Farbenschlag</h2>
	<div class='flex flex-row gap-x-2 justify-center'>
		<Select label='Sparte' bind:value={section} validator={validate.id} on:change={onSection}>
			{#each sections as section}
				<option value={section}>{section.name}</option>
			{/each}
		</Select>
		<Select class='w-96' label='Rasse' bind:value={breed} validator={validate.id}>
			<option value={null}>?</option>
			{#each breeds as breed}
				<option value={breed}>{breed.name}</option>
			{/each}
		</Select>
		<TextInput class='w-64' label='AOC Farbenschlag' bind:value={aocColor} validator={validate.name}/>
		<Submit class='py-0' noChangeValue='?' submitValue='+' invalidValue='X' errorValue='X' />
	</div>
</Form>

<style>

</style>