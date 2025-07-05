<script>
	import Form, {NumberInput, Select, Status, Submit, TextInput, validator} from '$lib/cmp/form/Form.svelte';
	import AocResult from './AocResult.svelte';
	import model from '$lib/js/model.js';
	import { ctx } from '$lib/js/store.svelte.js';

	let { data } = $props();

	let section = $state( null );
	let breeds = $derived( section && section.breeds ? section.breeds : [] );
	let breed = $state( null );
	let aocColor = $state( null );

	let results = $state( ctx.results ); //data.breeds );


	const validate = {
		// create
		id:     (v) => validator(v).notNull().isValid(), // object
		name:   (v) => validator(v).string().length( 2, 64 ).isValid(),
		//edit
		breeders     : (v) => validator(v).number().range( 1, 99999 ).orNull().isValid(),
		pairs        : (v) => validator(v).number().range( data.breeders, 99999 ).orNull().isValid(),
		lay: {
			dames: (v) => validator(v).number().range(1, 99999).orNull().isValid(),
			eggs: (v) => validator(v).number().range(0, 366).orNull().isValid(),
			weight: (v) => validator(v).number().range(1, 999).orNull().isValid(),
		},
		brood: {
			chicks: (v) => validator(v).number().if(data.pairs > 0).range(0, data.pairs * 50).orNull().isValid(),
			eggs: (v) => validator(v).number().range(1, 99999).orNull().isValid(),
			fertile: (v) => validator(v).number().range(0, data.brood.eggs).orNull().isValid(),
			hatched: (v) => validator(v).number().range(0, data.brood.fertile == null ? data.brood.eggs : data.brood.fertile).orNull().isValid(),
		},
		show: {
			count: (v) => validator(v).number().range(1, 99999).orNullIf(data.show.score == null).isValid(),
			score: (v) => validator(v).number().range(89, 97).orNullIf(data.show.count == null).isValid(),
		}
	}

	function newResult() {
		return {
			id:0, districtId:ctx.district.id, group:ctx.group, year:ctx.year,
			breeder:null, pairId:null,
			breeders:null, pairs:null,
			sectionId:section.id, breedId:breed.id, colorId:null, aocColor:'AOC '+aocColor,
			lay:{ dames:null, eggs:null, weight:null },
			brood:{ chicks:null, eggs:null, fertile:null, hatched:null},
			show:{ count:null, score:null },
		}
	}

	function onSubmitAdd( event ) {
		console.log('Submit' );
		const name = 'AOC: '+aocColor;
		const result = newResult();
		results = [ result, ...results ];
		ctx.breeds.push( result );// = [ result, ...ctx.breeds ];
		console.log( 'DB', results );
		return true;
	}

	$inspect('AOCCreate', data);

	$inspect('Section', section);


</script>

<Form class='bg-gray-50' onsubmit={onSubmitAdd}>
	<h2 class='text-center'>AOC Klasse, Farbenschlag hinzufügen</h2>
	<div class='flex flex-row gap-x-2 justify-center'>
		<Select label='Sparte' bind:value={section} validator={validate.id}>
			{#each ctx.standard.rootSections as section}
				<option value={section}>{section.name}</option>
			{/each}
		</Select>
		<Select class='w-96' label='Rasse' bind:value={breed} validator={validate.id} disabled={ section === null }>
			<option value={null}>?</option>
			{#if section && section.breeds }
				{#each section.breeds as breed}
					<option value={breed}>{breed.name}</option>
				{/each}
			{/if}
		</Select>
		<TextInput class='w-64' label='AOC Farbenschlag' bind:value={aocColor} validator={validate.name} disabled={ breed === null } />
		<Submit class='w-16 mt-3' noChangeValue='?' submitValue='+' invalidValue='X' errorValue='X' />
	</div>
</Form>




{#if results}{results.length}
	{#each results as result (result.id) } <!-- result.id as unique key -->
		<AocResult section={data.section} {result} {data}/>
	{/each}
{/if}



<style>
    .hasResult {
        @apply font-bold;
    }
    input[type='button'] {
        @apply text-white m-0 p-0;
    }

    button {
        vertical-align: text-top;
    }
</style>