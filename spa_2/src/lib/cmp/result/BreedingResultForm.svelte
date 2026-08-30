<script>
	import Form, { Select, Status, NumberInput, TextInput, validator } from '$lib/cmp/form/Form.svelte';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import { dec, selectName } from '$lib/js/tools.js';
	import model from '$lib/js/model.js';

	let { result=$bindable() } = $props();

	let section = $state( ctx.standard.sections[ result.sectionId ] );
	let breed = $state( ctx.standard.breeds[ result.breedId ] );
	let color = $state( ctx.standard.colors[ result.colorId ] );
	let pigeonBroods = $state( result.brood.eggs ? result.brood.eggs / 2 : null ); // helper for pigeons

	const validate = {
//        breeders     : (v) => validator(v).number().range( 1, 99999 ).orNull().isValid(),
		section	:	 (v) => validator(v).number().orNull().isValid(),
		breed	:	 (v) => validator(v).number().orNull().isValid(),
		color	:	 (v) => validator(v).number().orNull().isValid(),
		pairs        : (v) => validator(v).number().range( result.breeders, 99999 ).orNullIf( result.brood.hatched === null ).isValid(),
		lay: {
            //dames: (v) => validator(v).number().range(1, 99999).orNull().isValid(),
            eggs: (v) => validator(v).number().range(0, 366).orNull().isValid(),
            weight: (v) => validator(v).number().range(1, 999).orNull().isValid(),
        },
        brood: {
            broods: (v) => validator(v).number().range(0, 99999).orNull().isValid(),
            chicks: (v) => validator(v).number().range(0, pigeonBroods === null ? 99999 : pigeonBroods * 2 ).orNullIf( result.pairs === null && pigeonBroods === null ).isValid(),

            eggs: (v) => validator(v).number().range(1, 99999).orNull().isValid(),
            fertile: (v) => validator(v).number().range(0, result.brood.eggs ).orNull().isValid(),
            hatched: (v) => validator(v).number().range(0, result.brood.fertile == null ? result.brood.eggs : result.brood.fertile).orNull().isValid(),
        },
        show: {
            count    : (v) => validator(v).number().range( 1, 99999  ).orNullIf( result.show.score == null ).isValid(),
            score    : (v) => validator(v).number().range( 89, 97 ).orNullIf( result.show.count === null ).isValid(),
        },

    }

	function onSectionChange( event ) {
		console.log( 'Section changed', result.sectionId, event.target.value );
		section = ctx.standard.sections[ result.sectionId ];
		result.breedId = result.colorId = null;
		result.pairs = result.lay.eggs = result.lay.weight = null;
		result.brood.eggs = result.brood.fertile = result.brood.hatched = pigeonBroods = null;
		result.show.count = result.show.score = null;
	}
	function onBreedChange( event ) {
		console.log( 'Breed changed', result.breedId );
		breed = ctx.standard.breeds[ result.breedId ];
//		pair.colorId = color = null;
	}
	function onColorChange( event ) {
		console.log( 'Color changed' );
		color = ctx.standard.colors[ result.colorId ];
	}

	async function onSubmit( event ) {
        console.log( 'On Breederresult submit' );
        //await invalidate( 'results' ); // make results page reload data
        if( result && result.colorId ) { // valid entry
			if( result.sectionId === 5 ) { // pigeons then convert broods -> eggs
				result.brood.eggs = pigeonBroods > 0 ? pigeonBroods * 2 : null; // 2 eggs per brood expected
			}
			if( result.lay.eggs === null && result.lay.weight === null && result.brood.hatched === null && result.show.score === null ) {
				console.log( 'Result delete');
				if( result.id > 0 ) {
					return await model.Result.delete( result );
				}
				return true;
			} else {
				console.log( 'Save result' );
				return await model.Result.save( result );
			}
        }
        dirty.results++; // inc to trigger
    }
</script>

<Form class='flex flex-row gap-x-0.5 text-sm pt-2' autosubmit onsubmit={onSubmit}>
	<span class='pl-0 pt-3'>⤷</span>
	<Select class='w-28' label={'Sparte'} bind:value={ result.sectionId } onchange={ onSectionChange } validator={validate.section}>
		<option value={null} selected={ result.sectionId === null }>
			Sparte ?
		</option>
		{#each ctx.standard.rootSections as section }
			<option value={ section.id } selected={ section.id === result.sectionId }>
				{ section.name }
			</option>
		{/each}
	</Select>

	<Select class='w-48' label={'Rasse'} bind:value={ result.breedId } onchange={ onBreedChange } disabled={ section === null} validator={validate.breed}>
		<option value={null} selected={ result.breedId === null }>
			Rasse ?
		</option>
		{#if section}
			{#each section.breeds as breed }
				<option value={ breed.id } selected={ breed.id === result.breedId }>
					{ breed.name }
				</option>
			{/each}
		{/if}
	</Select>

	<Select class='w-48' label={'Farbe'} bind:value={ result.colorId } onchange={ onColorChange } disabled={ breed === null } validator={validate.color}>
		<option value={null} selected={ result.colorId === null }>
			Farbenschlag ?
		</option>
		{#if breed}
			{#each breed.colors as color }
				<option value={ color.id } selected={ color.id === result.colorId }>
					{color.name}
				</option>
			{/each}
		{/if}
	</Select>

	<span class='grow'></span>

	 {#if result.sectionId === 5 && result.colorId }
		<Select label='Gruppe*' bind:value={result.group}>
  			{#each [ 'I', 'II', 'III' ] as option}
  				<option value={option}>{option}</option>
  			{/each}
  		</Select>
		 <span class='w-1'></span>
		 <span class='w-12 pt-3 text-center'>-</span>
		 <span class='w-12 pt-3 text-center'>-</span>

		 <span class='w-1'></span>

		 <NumberInput class='w-14' label='Paare' bind:value={ result.pairs } validator={validate.pairs}></NumberInput>
		 <NumberInput class='w-14' label='Bruten' bind:value={ pigeonBroods } validator={validate.brood.broods}></NumberInput>
		 <NumberInput class='w-14' label='Geschlüpft' bind:value={ result.brood.hatched } validator={validate.brood.chicks}></NumberInput>

		 <span class='w-1'></span>

		 <NumberInput class='w-12' label='Tiere' bind:value={ result.show.count } validator={validate.show.count}></NumberInput>
		 <NumberInput class='w-14' label='Notes' bind:value={ result.show.score } min=89 max=97 step={0.1} error='89..97' validator={validate.show.score}></NumberInput>
		 <Status class='w-2' />
	 {:else if result.sectionId !== null && result.colorId}
		 <Select label='Gruppe*' bind:value={result.group}>
  			{#each [ 'I', 'II', 'III' ] as option}
  				<option value={option}>{option}</option>
  			{/each}
  		</Select>
		 <span class='w-1'></span>
		 <NumberInput class='w-12' label='Eier / J' bind:value={ result.lay.eggs } validator={validate.lay.eggs}></NumberInput>
		 <NumberInput class='w-12' label='Gewicht' bind:value={ result.lay.weight } validator={validate.lay.weight}></NumberInput>
		 <span class='w-1'></span>
		 <NumberInput class='w-14' label='Eigelegt' bind:value={ result.brood.eggs } validator={validate.brood.eggs}></NumberInput>
		 <NumberInput class='w-14' label='Befruchtet' bind:value={ result.brood.fertile } validator={validate.brood.fertile}></NumberInput>
		 <NumberInput class='w-14' label='Geschlüpft' bind:value={ result.brood.hatched } validator={validate.brood.hatched}></NumberInput>
		 <span class='w-1'></span>
		 <NumberInput class='w-12' label='Tiere' bind:value={ result.show.count } validator={validate.show.count}></NumberInput>
		 <NumberInput class='w-14' label='Notes' bind:value={ result.show.score } min=89 max=97 step={0.1} error='89..97' validator={validate.show.score}></NumberInput>
		 <Status class='w-2' />
	 	{:else}
		<span class='pt-3'>Felder erscheinen solbalt Farbenschlag bekannt</span>
	 {/if}
</Form>