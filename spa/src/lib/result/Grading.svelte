<script>
	import { meta } from 'tinro';
	import grading from '../../js/aab.js';
	import {dec} from '../../js/util.js';
	import { standard } from '../../js/store.js'
	import Form from '../common/form/Form.svelte';
	import Select from '../common/form/input/Select.svelte';
    import NumberInput from '../common/form/input/NumberInput.svelte';
    import TextInput from '../common/form/input/TextInput.svelte';
	import DateInput from '../common/form/input/DateInput.svelte';
	import Text from '../read/Text.svelte';
	import RingInput from '../common/form/input/RingInput.svelte';

	const route = meta();

	const sections = [ { id:3, name:'Groß und Wassergeflügel' }, { id:11, name:'Große Hühner' }, { id:12, name:'Zwerghühner und Wachteln' }, { id:5, name:'Tauben' } ];
    let breeds = null;
	let colors = null;

    let hasLayEggs = null;

    let hasBroods = null; // pigeons
    let hasBroodEggs = null; // layers
    let hasBroodChicks = null; // both

    function updateBreeds( event ) {
		const section = certificate.section;
        if( section && section.id > 0 ) {

			breeds = [];
            const foundSection = findSection( section.id, $standard );
            if( foundSection ) {
                collectBreeds(foundSection, breeds);
                breeds.sort( (a, b) => a.name < b.name ? -1 : a.name > b.name ? 1 : 0 );
            }
        }
    }

	function updateValues( event ) {
		hasLayEggs = null;
		hasBroods = null;
		hasBroodEggs = null;
		hasBroodChicks = null;
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
       // console.log( section.breeds );
        breeds.push( ...section.breeds );
        for( let childSection of section.children ) {
            collectBreeds( childSection, breeds );
        }
    }

	function gradeLay( lay ) {
		console.log( 'Lay', lay );

		lay.grade = certificate.breed && certificate.breed.layEggs && lay && lay.eggs > 0 ? grading.lay(lay.eggs, certificate.breed.layEggs) : '?';
		certificate = certificate;
	}

	function gradeBrood( brood ) {
		console.log( 'Brood', brood );
		brood.grade = brood.eggs > 0 && brood.hatched > 0 ? grading.brood.layers( brood.eggs, brood.hatched ) : null;
		certificate = certificate;
	}

	let certificate = { // layers

		number: null,
		date: null, show: null,
		breeder: null,
		section: null, breed:null, color:null,

		sire: {
			ring: null,
			parents: {
				sire: {ring: null, brood: {eggs: null, chicks: null, grade:null} },
				dame: {ring: null, lay: {eggs: null, grade:null}, brood: {eggs: null, hatched: null, grade:null },},
			}
		},
		dames: [
			{
				ring: null,
				parents: {
					sire: {ring: null, brood: {eggs: null, chicks: null, grade:null} },
					dame: {ring: null, lay: {eggs: null, grade:null}, brood: {eggs: null, hatched: null, grade:null },},
				},
			}, {
				ring: null,
				parents: {
					sire: {ring: null, brood: {eggs: null, chicks: null, grade:null} },
					dame: {ring: null, lay: {eggs: null, grade:null}, brood: {eggs: null, hatched: null, grade:null },},
				},
			}
		]
	}
</script>

<article class='w-256'>
	<Form autoSave={false}>
		<header>
			<h1 class='text-center'>BDRG Zuchtbuch</h1>
			<h1 class='text-center'>Abstammungsnachweis {#if certificate.section} {certificate.section.name} {/if}</h1>

			<fieldset class='flex flex-col'>
				<div class='flex flex-row'>
					<span class='w-32'> Ausstellung am </span>
					<DateInput />
					<span class='w-8 text-center'> in </span>
					<TextInput class='grow'/>
				</div>
			</fieldset>
		</header>
		<hr/>
		<main>
			<fieldset class='flex flex-row gap-x-1'>
				<span class='w-32'> Züchter </span>
				<TextInput class='grow' label='Züchter'/>
			</fieldset>
			<fieldset class='flex flex-row gap-x-1'>
				<span class='w-32'> Rasse </span>
				<Select class='w-60' label='Sparte *' bind:value={certificate.section} error='Pflichtfeld' on:change={updateBreeds}>
					<option value={null}>?</option>
					{#each sections as section }
						<option value={section}>{section.name}</option>
					{/each}
				</Select>

				<Select class='w-96' label={'Rasse *'} bind:value={certificate.breed} error='Pflichtfeld' on:change={updateValues}>
					<option value={null}>?</option>
					{#if breeds}
						{#each breeds as breed }
							<option value={breed}>{breed.name}</option>
						{/each}
					{/if}
				</Select>

				<Select class='grow' label={'Farbenschlag *'} bind:value={certificate.color} error='Pflichtfeld' on:change={updateValues}>
					<option value={null}>?</option>
					{#if certificate.breed}
						{#each certificate.breed.colors as color }
							<option value={color}>{color.name}</option>
						{/each}
					{/if}
				</Select>
			</fieldset>
		</main>
		<hr />
		<fieldset>
			<div class='flex flex-row'>
				<div class='w-64'>
					Stamm
				</div>
				<div class='grow'>
					Elterntiere
				</div>
			</div>

			<div class='flex flex-row'>
				<div class='w-64 flex flex-row'><span class='mt-3 mx-1'>1.0</span><RingInput class='w-32' label='Bundesring 1.0'/> </div>
				<div class='flex flex-col'>
					<div class='flex flex-row'> <!-- Sire -->
						<div class='flex flex-row'>
							<span class='my-3 mx-1'>1.0</span>
							<RingInput class='w-32' label='Bundesring 1.0'/>
						</div>
						<fieldset class='flex flex-row' on:input={gradeBrood(certificate.sire.parents.sire.brood)}>
							<span class='w-32 mt-3 mx-1'>→ Brutleistung</span>
							<NumberInput class='w-24' label='Eingelegte Eier' bind:value={certificate.sire.parents.sire.brood.eggs}  />
							<span class='w-8 mt-3 mx-1'>mit</span>
							<NumberInput class='w-24' label='Geschüpfte Küken' bind:value={certificate.sire.parents.sire.brood.hatched}  />
							<span class='w-4 mt-3 mx-1'>=</span>
							<output class='mt-3 mx-1 text-xl font-bold'>{certificate.sire.parents.sire.brood.grade ? dec(certificate.sire.parents.sire.brood.grade) : '?'}</output>
						</fieldset>
					</div>
					<div class='flex flex-row'> <!-- Dame -->
						<div class='flex flex-row'>
							<span class='my-3 mx-1'>1.0</span>
							<RingInput class='w-32' label='Bundesring 1.0'/>
						</div>
						<div class='flex flex-col'>
							<fieldset class='flex flex-row' on:input={gradeLay(certificate.sire.parents.dame.lay)}>
								<span class='w-32 mt-3 mx-1'> → Legeleistung</span>
								<NumberInput class='w-24' label='Legeleistung' bind:value={certificate.sire.parents.dame.lay.eggs}  />
								<span class='w-8 mt-3 mx-1'>und</span>
								<NumberInput class='w-24' label='SOLL Legeleistuing' value={certificate.breed ? certificate.breed.layEggs : '?'} disabled />
								<span class='w-4 mt-3 mx-1'>=</span>
								<output class='mt-3 mx-1 text-xl font-bold'>{certificate.sire.parents.dame.lay.grade ? dec(certificate.sire.parents.dame.lay.grade) : '?'}</output>
							</fieldset>
							<fieldset class='flex flex-row' on:input={gradeBrood(certificate.sire.parents.dame.brood)}>
								<span class='w-32 mt-3 mx-1'>→ Brutleistung</span>
								<NumberInput class='w-24' label='Eingelegte Eier' bind:value={certificate.sire.parents.dame.brood.eggs}  />
								<span class='w-8 mt-3 mx-1'>mit</span>
								<NumberInput class='w-24' label='Geschüpfte Küken' bind:value={certificate.sire.parents.dame.brood.hatched}  />
								<span class='w-4 mt-3 mx-1'>=</span>
								<output class='mt-3 mx-1 text-xl font-bold'>{certificate.sire.parents.dame.brood.grade ? dec(certificate.sire.parents.dame.brood.grade) : '?'}</output>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</fieldset>
		<footer>
			<fieldset>

			</fieldset>
		</footer>

	</Form>

</article>

<!--
<main>


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
						<div>Bewertung der Brutleistung laut AAB</div>
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
							<div>Bewertung der Legeleistung laut AAB</div>
							<div class='flex gap-x-2'>
								<NumberInput class='w-20' value={breed.layEggs} label='Soll legen' title='Legeleistung laut Standard' disabled/>
								<NumberInput class='w-20' bind:value={hasLayEggs} label='Hat gelegt' title='Legeleistung in Eier / Jahr'/>
								 =>
								<NumberInput class='w-32 text-right' value={grading.lay( hasLayEggs, breed.layEggs )} label='Bewertung in punkte' title='Legeleistung in punkte laut H5 AAB' disabled/>
							</div>
						</fieldset>
						<fieldset class='border border-gray-400 rounded p-2'>
							<div>Bewertung der Brutleistung laut AAB</div>
							<div class='flex gap-x-2'>
								<NumberInput class='w-20' bind:value={hasBroodEggs} label='Eingelegt' title='Eingelegte Eier'/>
								<NumberInput class='w-20' bind:value={hasBroodChicks} label='Küken' title='Zahl der aufgezogene Küken'/>
								=>
								<NumberInput class='w-32' value={grading.brood.layers( hasBroodEggs, hasBroodChicks )} label='Bewertung in punkte' title='Brutleistung in punkte laut H5 AAB' disabled/>
							</div>
						</fieldset>
					</div>
				{/if}
			{/if}
		</fieldset>
	</Form>
</main>
-->
<style>

</style>