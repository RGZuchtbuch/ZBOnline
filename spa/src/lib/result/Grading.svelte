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
	import RingInput from '../common/form/input/RingInput.svelte';

	const route = meta();

	const sections = [ { id:3, name:'Groß und Wassergeflügel' }, { id:11, name:'Große Hühner' }, { id:12, name:'Zwerghühner und Wachteln' }, { id:5, name:'Tauben' } ];
    let breeds = null;
	let colors = null;

	let pair = null;

	createData();

	function createData() {
		pair = {
			number: null,
			date: null, show: null,
			breeder: null,
			section: null, breed: null, color: null,
			grade: '?'
		}
		clearResults();
	}

	function clearResults() {
		pair.animals = [];
		if( pair.section ) {
			const max = pair.section.id === PIGEONS ? 2 : 3;
			for (let i = 0; i < max; i++) {
				pair.animals.push({
					ring: null, grade: '?',
					parents: [
						{ sex:'1.0', ring: null, brood: {eggs: null, chicks: null, grade: '?'}},
						{ sex:'0.1', ring: null, lay: {eggs: null, grade: '?'}, brood: {eggs: null, hatched: null, grade: '?'}},
					],
				});
			}
		}
	}

    function updateBreeds( event ) {
		const section = pair.section;
        if( section && section.id > 0 ) {
			breeds = [];
            const foundSection = findSection( section.id, $standard );
            if( foundSection ) {
                collectBreeds(foundSection, breeds);
                breeds.sort( (a, b) => a.name < b.name ? -1 : a.name > b.name ? 1 : 0 );
            }
			clearResults();
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
       // console.log( section.breeds );
        breeds.push( ...section.breeds );
        for( let childSection of section.children ) {
            collectBreeds( childSection, breeds );
        }
    }

	function gradeLay( lay ) {
		console.log( 'Pair', pair );
		lay.grade = pair.breed && pair.breed.layEggs > 0 && lay && lay.eggs > 0 ? grading.lay(lay.eggs, pair.breed.layEggs) : '?';
		recalculateGrades();
	}

	function gradeBrood( brood ) {
		brood.grade = brood && brood.eggs > 0 && brood.hatched > 0 ? grading.brood.layers(brood.eggs, brood.hatched) : '?';
		recalculateGrades();
	}

	function recalculateGrades() {
		console.log( 'grade pair' );

		if( pair.section && pair.breed ) {
			for( const animal of pair.animals ) {
				if( pair.section.id === PIGEONS ) {
					if( animal.parents[0].brood.grade !== '?' && animal.parents[1].brood.grade !== '?' ) {
						animal.grade = ( animal.parents[0].brood.grade + animal.parents[1].brood.grade ) / 2.0;
					} else {
						animal.grade = '?';
					}
					console.log( 'Todo'); // TODO
				} else { // layer
					if( animal.parents[0].brood.grade !== '?' && animal.parents[1].brood.grade !== '?' && animal.parents[1].lay.grade !== '?' ) {
						animal.grade = (animal.parents[0].brood.grade + animal.parents[1].brood.grade + animal.parents[1].lay.grade) / 3.0;
					} else {
						animal.grade = '?';
					}
				}
			}

			if( pair.animals.every( (animal) => animal.grade !== '?' ) ) {
				console.log('Pair grade', pair, pair.animals.length );
				pair.grade = pair.animals.reduce( ( sum, current ) => sum + current.grade, 0 ) / pair.animals.length;
				return; // success
			}
		}
		pair.grade = '?';
	}


	function gradeAnimal( animal ) {
		animal.grade = Math.round( (animal.parents['1.0'].brood.grade + animal.parents['0.1'].brood.grade + 2 * animal.parents['0.1'].lay.grade ) / 4 );
		gradePair();
		return animal.grade;
	}

	function gradePair() {
		console.log( 'grade pair' );
		const animals = [ pair.sire, pair.dames[0], pair.dames[1] ];
		let sum = 0;
		for( const animal of animals ) {
			if( animal.grade === '?' ) return '?';
			sum += animal.grade;
		}
		pair.grade = Math.round( sum / animals.length );
	}

	function grade( value ) {
		return value === '?' ? '?' : value.toFixed(1);
	}

</script>

<main class='w-256 p-4 bg-gray-100'>
	<Form autoSave={false}>
		<header>
			<h1 class='text-center'>BDRG Zuchtbuch</h1>

			<div class='flex flex-row px-2 justify-center'>
				<h3 class='-mt-1 mx-2 text-center'>Abstammungsnachweis</h3>
				<Select class='w-60' label='Sparte *' bind:value={pair.section} error='Pflichtfeld' on:change={updateBreeds}>
					<option value={null}>?</option>
					{#each sections as section }
						<option value={section}>{section.name}</option>
					{/each}
				</Select>

			</div>

			<fieldset class='flex flex-col'>
				<div class='flex flex-row gap-x-1'>
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
				<TextInput class='grow' label='Züchter'/><TextInput class='w-32' label='Zuchtbuch-Nr' />
			</fieldset>
			<fieldset class='flex flex-row gap-x-1'>
				<span class='w-32'> Rasse </span>


				<Select class='w-104' label={'Rasse *'} bind:value={pair.breed} error='Pflichtfeld' on:change={clearResults}>
					<option value={null}>?</option>
					{#if breeds}
						{#each breeds as breed }
							<option value={breed}>{breed.name}</option>
						{/each}
					{/if}
				</Select>

				<Select class='grow' label={'Farbenschlag *'} bind:value={pair.color} error='Pflichtfeld'>
					<option value={null}>?</option>
					{#if pair.breed}
						{#each pair.breed.colors as color }
							<option value={color}>{color.name}</option>
						{/each}
					{/if}
				</Select>
			</fieldset>
		</main>
		<hr />
		{#if pair.breed}
			<div class='flex flex-row'>
				<div class='w-64 mx-4'>
					Stamm
				</div>
				<div class='grow'>
					Elterntiere
				</div>
			</div>
			{#each pair.animals as animal }
				<fieldset class='border border-gray-400 rounded p-2'>


					<div class='flex flex-row'>
						<div class='flex flex-col'>
							<div class='flex flex-row p-4'>
								<span class='w-8 mt-3 mx-2'>1.0</span>
								<RingInput class='w-32' label='Bundesring 1.0'/>
							</div>
							<output class='pt-8 text-2xl text-center'>{ grade( animal.grade ) }</output>
						</div>
						<div class='flex flex-col gap-y-1'>
							{#each animal.parents as parent}
								<div class='flex flex-row border border-gray-400 rounded p-2'> <!-- parent -->
									<div class='flex flex-row'>
										<span class='w-8 my-3 mx-1'>{parent.sex}</span>
										<RingInput class='w-32' label='Bundesring {parent.sex}'/>
									</div>

									<div class='flex flex-col'>
										{#if parent.sex === '0.1'}
<!--											<fieldset class='flex flex-row ' on:input={gradeLay(animal.parents[parentSex].lay)}> -->
											<fieldset class='flex flex-row ' on:input={ () => gradeLay( parent.lay ) }>
												<span class='w-32 mt-3 mx-1'> → Legeleistung</span>
												<NumberInput class='w-24' label='Legeleistung' bind:value={parent.lay.eggs} />
												<span class='w-8 mt-3 mx-1'>und</span>
												<NumberInput class='w-24' label='SOLL Legeleistuing' value={pair.breed ? pair.breed.layEggs : '?'} disabled />
												<span class='w-4 mt-3 mx-1'>=</span>
												<output class='mt-3 mx-1 text-xl font-bold'>{parent.lay.grade}</output>
											</fieldset>
										{/if}

										<fieldset class='flex flex-row ' on:input={ () => gradeBrood( parent.brood ) }>
											<span class='w-32 mt-3 mx-1'>→ Brutleistung</span>
											<NumberInput class='w-24' label='Eingelegte Eier' bind:value={parent.brood.eggs}  />
											<span class='w-8 mt-3 mx-1'>mit</span>
											<NumberInput class='w-24' label='Geschüpfte Küken' bind:value={parent.brood.hatched}  />
											<span class='w-4 mt-3 mx-1'>=</span>
											<output class='mt-3 mx-1 text-xl font-bold'>{parent.brood.grade}</output>
										</fieldset>

									</div>
								</div>
							{/each}
						</div>
					</div>
				</fieldset>
				<hr />
			{/each}

			<footer class='flex flex-row'>
				<div class='grow'>
					Datum / Unterschrift
				</div>
				<div class='w-64 my-4 flex flex-col font-bold text-2xl'>
					<div class='text-center'>Leistungsnote</div>
					<strong class='m-4 text-center'>{grade(pair.grade)}</strong>
				</div>
			</footer>
		{/if}


	</Form>

</main>

<style>

</style>