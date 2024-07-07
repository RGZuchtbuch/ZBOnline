<script>
	import { slide } from 'svelte/transition';
    import { meta } from 'tinro';
	import grading from '../../js/aab.js';
	import {dat, dec} from '../../js/util.js';
	import { standard } from '../../js/store.js'
	import validator from '../../js/validator.js';

	import Form from '../common/form/Form.svelte';
	import Select from '../common/form/input/Select.svelte';
    import NumberInput from '../common/form/input/NumberInput.svelte';


	const route = meta();

	const sections = [ { id:3, name:'Groß und Wassergeflügel', display:'Hühner' }, { id:11, name:'Große Hühner', display:'Hühner' }, { id:12, name:'Zwerghühner und Wachteln', display:'Hühner' }, { id:5, name:'Tauben', display:'Tauben' } ];
    let breeds = null;


	let section = null;
	let breed = null;
	let lay   = { eggs:null, grade:null };
	let brood = { count:null, eggs:null, hatched:null, grade:null };

    const validate = {
        layer: {
            lay: {
                eggs:       (v) => validator(v).range( 0, 366 ).orNull().isValid(),
            },
	        brood: {
                eggs:       (v) => validator(v).range( 0, 9999 ).orNull().isValid(),
                hatched:    (brood) => (v) => validator(v).range(0, brood.eggs).orNull().isValid(),
            },
        },
	    pigeon: {
            brood: {
                count:      (v) => validator(v).range( 0, 99 ).orNull().isValid(),
	            hatched:    (brood) => (v) => validator(v).range( 0, 2 * brood.count ).orNull().isValid(),
            },
	    }
    }

	function breedChanged() {
		lay.eggs = lay.grade = null;
		brood.count = brood.eggs = brood.hatched = brood.grade = null;
	}

	function sectionChanged(event ) {
		console.log( 'Section Changed' );
        if( section && section.id > 0 ) {
			breeds = [];
            const foundSection = findSection( section.id, $standard );
            if( foundSection ) {
                collectBreeds(foundSection, breeds);
                breeds.sort( (a, b) => a.name < b.name ? -1 : a.name > b.name ? 1 : 0 );
            }
			breed = null;
			breedChanged();
        }
    }

    function findSection( id, section ) { // find section to id
        if( section.id === id ) return section;
        for( let child of section.children ) {
            const foundSection = findSection( id, child );
            if( foundSection ) return foundSection;
        }
        return null;
    }

    function collectBreeds( section, breeds ) { // collect breeds for sections and it's subsections
        breeds.push( ...section.breeds );
        for( let childSection of section.children ) {
            collectBreeds( childSection, breeds );
        }
    }

	function gradeLay( lay ) {
		console.log( 'Grade Lay', lay );
		if( breed && breed.layEggs > 0 && lay && lay.eggs > 0 ) {
			const grade = grading.lay(lay.eggs, breed.layEggs);
			console.log( 'G', grade );
			lay.grade = grade === null ? '?' : grade;
			lay = lay;
		}
		console.log( 'Grade Lay after', lay );
	}

	function gradeBrood( brood ) {
		console.log( 'Grade Brood', brood);
		if( section ) {
			if( section.id === PIGEONS ) {
				if( brood && brood.count > 0 && brood.hatched !== null ) {
					const grade = grading.brood.pigeons( breed.broodGroup, brood.count, brood.hatched );
					brood.grade = grade === null ? '?' : grade;
				} else {
					brood.grade = '?';
				}
			} else { // layers
				if( brood && brood.eggs > 0 && brood.hatched !== null ) {
					const grade = grading.brood.layers( brood.eggs, brood.hatched );
					brood.grade = grade === null ? '?' : grade;
				} else {
					brood.grade = '?';
				}
			}
		} else {
			brood.grade = '?';
		}
	}


	function grade( value, dec = 0 ) {
		console.log( 'Grade', value );
		return value === null ? '?' : value === 0 ? '0' : value === '?' ? '?' : value.toFixed( dec );
	}

	$: gradeLay( lay );
	$: gradeBrood( brood );

</script>

<main class='w-full p-4 bg-gray-100 text-xl transition:slide'>
	<Form autoSave={false}>
		<header>
		</header>

		<main>
			<fieldset class='flex flex-col'>
				<div class='flex flex-row gap-x-4'>
					<span class='w-40 mt-6'> Sparte </span>
					<Select class='w-96' label='Sparte *' bind:value={section} error='Pflichtfeld' on:change={sectionChanged}>
						<option value={null}>?</option>
						{#each sections as section }
							<option value={section}>{section.name}</option>
						{/each}
					</Select>
				</div>
				<div class='flex flex-row gap-x-4'>
					<span class='w-40 mt-6'> Rasse </span>
					<Select class='w-96' label={'Rasse *'} bind:value={breed} error='Pflichtfeld' on:change={breedChanged}>
						<option value={null}>?</option>
						{#if breeds}
							{#each breeds as breed }
								<option value={breed}>{breed.name}</option>
							{/each}
						{/if}
					</Select>
					<div class='mt-6'>
						{#if section && section.id === PIGEONS && breed } in Brutgruppe {breed.broodGroup } {/if}
						{#if section && section.id !== PIGEONS && breed } soll legen {breed.layEggs } {/if}
					</div>
				</div>
			</fieldset>
		</main>
		<hr>
		{#if section && breed}
			<div class='flex flex-col'>
				{#if section.id === PIGEONS}
					<fieldset class='flex flex-row gap-x-4 '>
						<span class='w-40 mt-6 text-left'>Brutleistung </span>
						<NumberInput class='w-24' label='Bruten' bind:value={brood.count} validator={validate.pigeon.brood.count} />
						<span class='w-8 mt-6'>mit</span>
						<NumberInput class='w-32' label='Beringte Jungtauben' bind:value={brood.hatched} validator={validate.pigeon.brood.hatched( brood )}  />
						<span class='w-4 mt-6'>→</span>
						<output class='w-8 mt-6 text-xl font-bold text-center'>{ grade( brood.grade )}</output>
					</fieldset>
				{:else}
					<fieldset class='flex flex-row gap-x-4'>
						<div class='w-40 mt-6 text-left'>Legeleistung</div>
						<NumberInput class='w-32' label='Legen e/j' bind:value={ lay.eggs } validator={validate.layer.lay.eggs}/>
						<div class='w-8 mt-6 text-center'>von</div>
						<NumberInput class='w-32' label='SOLL Legen' value={breed ? breed.layEggs : '?'} disabled />
						<div class='w-4 mt-6 text-center'>→</div>
						<output class='w-8 mt-6 text-xl font-bold text-center'>{ grade( lay.grade ) }</output>
					</fieldset>

					<fieldset class='flex flex-row gap-x-4'>
						<span class='w-40 mt-6 text-left'>Brutleistung</span>
						<NumberInput class='w-32' label='Eingelegt' bind:value={brood.eggs} validator={ validate.layer.brood.eggs } />
						<span class='w-8 mt-6 text-center'>mit</span>
						<NumberInput class='w-32' label='Geschüpft' bind:value={brood.hatched} validator={ validate.layer.brood.hatched(brood) } />
						<span class='w-4 mt-6 text-center'>→</span>
						<output class='w-8 mt-6 text-xl font-bold text-center'>{ grade( brood.grade )}</output>
					</fieldset>
				{/if}
			</div>
		{:else}
			<div class='text-center italic'>Der Leistungsdatenteil erscheint sobalt die Rasse eingegeben ist</div>
		{/if}
	</Form>

</main>

<style>

</style>