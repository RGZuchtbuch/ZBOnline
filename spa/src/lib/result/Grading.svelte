<script>
	import { meta } from 'tinro';
	import grading from '../../js/aab.js';
	import { standard } from '../../js/store.js'
	import Form from '../common/form/Form.svelte';
	import Select from '../common/form/input/Select.svelte';
    import NumberInput from '../common/form/input/NumberInput.svelte';
    import TextInput from '../common/form/input/TextInput.svelte';

	const route = meta();

    let section = null;
    let breed = null;
	const sections = [ { id:3, name:'Groß und Wassergeflügel' }, { id:11, name:'Große Hühner' }, { id:12, name:'Zwerghühner und Wachteln' }, { id:5, name:'Tauben' } ];
    let breeds = null;

    let hasLayEggs = null;

    let hasBroods = null; // pigeons
    let hasBroodEggs = null; // layers
    let hasBroodChicks = null; // both

    function updateBreeds( event ) {
        if( section && section.id > 0 ) {
			breeds = [];
            const foundSection = findSection( section.id, $standard );
            if( foundSection ) {
                collectBreeds(foundSection, breeds);
                breeds.sort( (a, b) => a.name < b.name ? -1 : a.name > b.name ? 1 : 0 )
            }
        }
    }

	function updateValues( event ) {
		hasLayEggs = null;
		hasBroods = null;
		hasBroodEggs = null;
		hasBroodChicks = null;
	}

    function findSection( id, section ) {
        if( section.id === id ) return section;
        for( let child of section.children ) {
            const foundSection = findSection( id, child );
            if( foundSection ) return foundSection;
        }
        return null;
    }

    function collectBreeds( section, breeds ) {
       // console.log( section.breeds );
        breeds.push( ...section.breeds );
        for( let childSection of section.children ) {
            collectBreeds( childSection, breeds );
        }
    }



    $: console.log( 'Breed', breed );
//    $: console.log( 'Standard', $standard );

</script>

<div>
	Nachweise berechnen, provisorisch

	<div>Rasse</div>
	<Form>
		<fieldset class='flex flex-row px-2 gap-x-1'>
			<Select class='w-60' label='Sparte *' bind:value={section} error='Pflichtfeld' on:change={updateBreeds}>
				<option value={null}></option>
				{#each sections as section }
					<option value={section}>{section.name}</option>
				{/each}
			</Select>

			<Select class='w-104' label={'Rasse *'} bind:value={breed} error='Pflichtfeld' on:change={updateValues}>
				<option value={null}></option>
				{#if breeds}
					{#each breeds as breed }
						<option value={breed}>{breed.name}</option>
					{/each}
				{/if}
			</Select>
		</fieldset>
		<fieldset>
			{#if section && breed}
				{#if section.id === PIGEONS }
					<fieldset class='border border-gray-400 rounded p-2'>
						<div>Bewertung der Brutleistung</div>
						<div class='flex gap-x-2'>
							<TextInput class='w-8' value={breed.broodGroup} label='Grp' title='Brutgruppen zur berechnung der Leistungsnote' disabled/>
							<NumberInput class='w-20' bind:value={hasBroods} label='Bruten' title='Zahl der bruten/Nester'/>
							<NumberInput class='w-20' bind:value={hasBroodChicks} label='Küken' title='Zahl der aufgezogene Küken'/>
							=>
							<TextInput class='w-20' value={grading.brood.pigeons( breed.broodGroup, hasBroods, hasBroodChicks )} label='Bewertung' title='Brutleistung' disabled/>
						</div>
					</fieldset>
				{:else}
					<div class='flex flex-col gap-y-2'>
						<fieldset class='border border-gray-400 rounded p-2'>
							<div>Bewertung der Legeleistung</div>
							<div class='flex gap-x-2'>
								<TextInput class='w-20' value={breed.layEggs} label='Soll legen' title='Legeleistung laut Standard' disabled/>
								<NumberInput class='w-20' bind:value={hasLayEggs} label='Hat gelegt' title='Legeleistung in Eier / Jahr'/>
								 =>
								<TextInput class='w-20' value={grading.lay( hasLayEggs, breed.layEggs )} label='Bewertung' title='Legeleistung' disabled/>
							</div>
						</fieldset>
						<fieldset class='border border-gray-400 rounded p-2'>
							<div>Bewertung der Brutleistung</div>
							<div class='flex gap-x-2'>
								<NumberInput class='w-20' bind:value={hasBroodEggs} label='Eingelegt' title='Eingelegte Eier'/>
								<NumberInput class='w-20' bind:value={hasBroodChicks} label='Küken' title='Zahl der aufgezogene Küken'/>
								=>
								<TextInput class='w-20' value={grading.brood.layers( hasBroodEggs, hasBroodChicks )} label='Bewertung' title='Brutleistung' disabled/>
							</div>
						</fieldset>
					</div>
				{/if}
			{/if}
		</fieldset>
	</Form>
</div>

<style>

</style>