<script>
	import store from '$lib/js/store.svelte.js';
	import Form, {Select, Submit, TextInput, validator} from '$lib/cmp/form/Form.svelte';

	let { district, results, year } = $props();
	// export let results;
	// export let districtId;
	// export let year;

	let section = $state( null );
	let breeds = $derived( section ? section.breeds : [] );
	let breed = $state( null );
	let aocColor = $state( null );

	const validate = {
		id:     (v) => validator(v).notNull().isValid(), // object
		name:   (v) => validator(v).string().length( 2, 64 ).isValid(),
	}

	// function findSection( id, section ) {
	// 	if( section.id === id ) return section;
	// 	for( const childSection of section.children ) {
	// 		const found = findSection( id, childSection );
	// 		if( found ) return found;
	// 	}
	// 	return null; // nothing found
	// }

	// function collectBreeds( breeds, section ) {
	// 	for( const breed of section.breeds ) breeds.push( breed );
	// 	for( let childSection of section.children ) {
	// 		collectBreeds( breeds, childSection );
	// 	}
	// }

	function newResult() {
		return {
			id:0, districtId:district.id, group:'I', year:year,
			breeder:null, pairId:null,
			breeders:null, pairs:null,
			sectionId:section.id, breedId:breed.id, colorId:null, aocColor:'AOC '+aocColor,
			lay:{ dames:null, eggs:null, weight:null },
			brood:{ chicks:null, eggs:null, fertile:null, hatched:null},
			show:{ count:null, score:null },
		}
	}

	function onSection( event ) {

		// breeds = [];
		// aocColor = null;
		// let selectedSection = store.standard.sections[ section.id, stors$standard); // start at geflügel
		// if( selectedSection ) {
		// 	console.log( selectedSection );
		// 	collectBreeds( breeds, selectedSection ); // in section and subs
		// 	breeds.sort( (a, b) => a.name < b.name ? -1 : a.name > b.name ? 1 : 0 );
		//
		// 	console.log('Bb', breeds );
		// }
	}

	function onSubmit( event ) {
		console.log('Submit' );
		const name = 'AOC: '+aocColor;
		const result = newResult();
		results.unshift( result );
		return true;
	}


</script>

<Form onsubmit={onSubmit}>
	<h2 class='text-center'>AOC Klasse, neuer Farbenschlag</h2>
	<div class='flex flex-row gap-x-2 justify-center'>
		<Select label='Sparte' bind:value={section} validator={validate.id} on:change={onSection}>
			{#each store.standard.rootSections as section}
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