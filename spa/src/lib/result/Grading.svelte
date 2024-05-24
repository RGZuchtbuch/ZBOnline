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

	const sections = [ { id:3, name:'Groß und Wassergeflügel', display:'Hühner' }, { id:11, name:'Große Hühner', display:'Hühner' }, { id:12, name:'Zwerghühner und Wachteln', display:'Hühner' }, { id:5, name:'Tauben', display:'Tauben' } ];
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
					sex: i === 0 ? '1.0' : '0.1', ring: null, grade: '?',
					parents: [
						{ sex:'1.0', ring: null, brood: { broods:null, eggs: null, hatched: null, grade: '?'}, grade: '?'},
						{ sex:'0.1', ring: null, lay: {eggs: null, grade: '?'}, brood: { broods:null, eggs: null, hatched: null, grade: '?'}, grade: '?'},
					],
				});
			}
		}
		console.log( 'Pair', pair );
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
        breeds.push( ...section.breeds );
        for( let childSection of section.children ) {
            collectBreeds( childSection, breeds );
        }
    }

	function gradeLay( lay ) {
		lay.grade = pair.breed && pair.breed.layEggs > 0 && lay && lay.eggs > 0 ? grading.lay(lay.eggs, pair.breed.layEggs) : '?';
		recalculateGrades();
	}

	function gradeBrood( brood ) {
		if( pair.section ) {
			if( pair.section.id === PIGEONS ) {
				brood.grade = brood && brood.count > 0 && brood.hatched !== null ? grading.brood.pigeons( pair.breed.broodGroup, brood.count, brood.hatched) : '?';
			} else {
				brood.grade = brood && brood.eggs > 0 && brood.hatched !== null ? grading.brood.layers(brood.eggs, brood.hatched) : '?';
			}
		} else {
			brood.grade = '?';
		}
		recalculateGrades();
	}

	function recalculateGrades() {
		console.log( 'grade pair' );

		if( pair.section && pair.breed ) {
			for( const animal of pair.animals ) {
				for( const parent of animal.parents ) {
					if( pair.section.id !== PIGEONS && parent.sex === '0.1') {
						parent.grade = parent.brood.grade !== '?' && parent.lay.grade !== '?' ? ( parent.brood.grade + parent.lay.grade ) / 2.0 : '?';
					} else {
						parent.grade = parent.brood.grade !== '?' ? parent.brood.grade : '?';
					}
				}

				if( animal.parents.every( (parent) => parent.grade !== '?' ) ) {
					animal.grade = animal.parents.reduce( ( sum, current ) => sum + current.grade, 0 ) / animal.parents.length;
				} else {
					animal.grade = '?';
				}
			}

			if( pair.animals.every( (animal) => animal.grade !== '?' ) ) {
				pair.grade = pair.animals.reduce( ( sum, current ) => sum + current.grade, 0 ) / pair.animals.length;
				return;
			}
		}
		pair.grade = '?';
	}

	function recalculateGradesa() {
		console.log( 'grade pair' );

		if( pair.section && pair.breed ) {
			for( const animal of pair.animals ) {
				if( pair.section.id === PIGEONS ) {
					if( animal.parents[0].brood.grade !== '?' && animal.parents[1].brood.grade !== '?' ) {
						animal.grade = ( animal.parents[0].brood.grade + animal.parents[1].brood.grade ) / 2.0;
					} else {
						animal.grade = '?';
					}
				} else { // layer
					if( animal.parents[0].brood.grade !== '?' && animal.parents[1].brood.grade !== '?' && animal.parents[1].lay.grade !== '?' ) {
						animal.grade = (animal.parents[0].brood.grade + ( animal.parents[1].brood.grade + animal.parents[1].lay.grade) / 2.0 ) / 2.0;
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

	function grade( value, dec = 0 ) {
		return value === '?' ? '?' : value.toFixed(0)+' p.';
	}

</script>

<main class='w-256 p-4 bg-gray-100 text-xl'>
	<Form autoSave={false}>
		<header>
			<h1 class='text-center'>BDRG Zuchtbuch</h1>

			<div class='flex flex-row gap-x-4 justify-center'>
				<h2 class='w-64 text-right'>Abstammungsnachweis</h2>
				<h2 class='w-64 text-left'>{pair.section ? pair.section.display :'?'}</h2>
			</div>

			<fieldset class='flex flex-col'>
				<div class='flex flex-row gap-x-1'>
					<span class='w-40 mt-6'> Ausstellung am </span>
					<DateInput label='Datum'/>
					<span class='w-8 mt-6 text-center'> in </span>
					<TextInput class='grow' label='Schau'/>
				</div>
			</fieldset>
		</header>

		<main>
			<fieldset class='flex flex-row gap-x-1'>
				<span class='w-40 mt-6'> Züchter </span>
				<TextInput class='grow' label='Züchter'/><TextInput class='w-32' label='Zuchtbuch-Nr' />
			</fieldset>
			<fieldset class='flex flex-col gap-x-1'>
				<div class='flex flex-row no-print'>
					<span class='w-40 mt-6'> Sparte </span>
					<Select class='w-60' label='Sparte *' bind:value={pair.section} error='Pflichtfeld' on:change={updateBreeds}>
						<option value={null}>?</option>
						{#each sections as section }
							<option value={section}>{section.name}</option>
						{/each}
					</Select>
				</div>
				<div class='flex flex-row'>
					<span class='w-40 mt-6'> Rasse </span>
					<Select class='w-104' label={'Rasse *'} bind:value={pair.breed} error='Pflichtfeld' on:change={clearResults}>
						<option value={null}>?</option>
						{#if breeds}
							{#each breeds as breed }
								<option value={breed}>{breed.name}</option>
							{/each}
						{/if}
					</Select>

					<Select class='w-104' label={'Farbenschlag'} bind:value={pair.color} error='Pflichtfeld'>
						<option value={null}>?</option>
						{#if pair.breed}
							{#each pair.breed.colors as color }
								<option value={color}>{color.name}</option>
							{/each}
						{/if}
					</Select>
				</div>
			</fieldset>
		</main>

		{#if pair.breed}
			<div class='flex flex-col gap-y-2 mt-4'>
				<div class='flex flex-row'>
					<div class='w-64 mx-4'>
						Stamm
					</div>
					<div class='grow'>
						Elterntiere {pair.breed.broodgroup}
					</div>
				</div>
				{#each pair.animals as animal }
					<fieldset class='border border-gray-400 rounded p-2'>


						<div class='flex flex-row'>
							<div class='flex flex-col'>
								<div class='flex flex-row p-4'>
									<span class='w-8 mt-6 mx-2'>{animal.sex}</span>
									<RingInput class='w-32' label='Bundesring {animal.sex}'/>
								</div>
								<output class='text-2xl text-center font-bold'>{ grade( animal.grade ) }</output>
							</div>
							<div class='flex flex-col gap-y-1'>
								{#if pair.section.id === PIGEONS}
									<div class='flex flex-row'>
										<div class='flex flex-col'>
											{#each animal.parents as parent}
												<div class='flex flex-row'>
													<span class='w-8 my-6 mx-1'>{parent.sex}</span>
													<RingInput class='w-32' label='Bundesring {parent.sex}'/>
													<fieldset class='flex flex-row gap-x-2' on:input={ () => gradeBrood( parent.brood ) }>
														<span class='w-36 mt-6'>→ Brutleistung</span>
														<NumberInput class='w-12' label='Gruppe' value={pair.breed.broodGroup} disabled />
														<NumberInput class='w-32' label='Bruten' bind:value={parent.brood.count}  />
														<span class='w-8 mt-6'>mit</span>
														<NumberInput class='w-32' label='Beringte Jungtauben' bind:value={parent.brood.hatched}  />
														<span class='w-4 mt-6'>=</span>
														<output class='w-16 mt-6 mx-1 text-xl font-bold'>{ grade( parent.brood.grade )}</output>
													</fieldset>
												</div>
											{/each}
										</div>

									</div>
								{:else}
									{#each animal.parents as parent}
										<div class='flex flex-row border border-gray-400 rounded p-2'> <!-- parent -->
											<div class='flex flex-col'>
												<div class='flex flex-row'>
													<span class='w-8 my-6 mx-1'>{parent.sex}</span>
													<RingInput class='w-32' label='Bundesring {parent.sex}'/>
												</div>
												{#if parent.sex === '0.1'}
													<div class='text-center font-bold'>{ grade( parent.grade )}</div>
												{/if}
											</div>

											<div class='flex flex-col'>
												{#if parent.sex === '0.1'}
													<fieldset class='flex flex-row' on:input={ () => gradeLay( parent.lay ) }>
														<span class='w-36 mt-6 mx-1'> → Legeleistung</span>
														<NumberInput class='w-32' label='Legeleistung' bind:value={ parent.lay.eggs } />
														<span class='w-8 mt-6 mx-1'>und</span>
														<NumberInput class='w-32' label='SOLL Legeleistuing' value={pair.breed ? pair.breed.layEggs : '?'} disabled />
														<span class='w-4 mt-6 mx-1'>=</span>
														<output class='w-16 mt-6 mx-1 text-xl font-bold'>{ grade( parent.lay.grade) }</output>
													</fieldset>
												{/if}

												<fieldset class='flex flex-row ' on:input={ () => gradeBrood( parent.brood ) }>
													<span class='w-36 mt-6 mx-1'>→ Brutleistung</span>
													<NumberInput class='w-32' label='Eingelegte Eier' bind:value={parent.brood.eggs}  />
													<span class='w-8 mt-6 mx-1'>mit</span>
													<NumberInput class='w-32' label='Geschüpfte Küken' bind:value={parent.brood.hatched}  />
													<span class='w-4 mt-6 mx-1'>=</span>
													<output class='w-16 mt-6 mx-1 text-xl font-bold'>{ grade( parent.brood.grade )}</output>
												</fieldset>
											</div>
										</div>
									{/each}
								{/if}

							</div>
						</div>
					</fieldset>
				{/each}
			</div>

			<footer class='flex flex-row align-stretch gap-x-4'>
				<div class='flex grow border border-gray-600 px-4'>
					Datum / Unterschrift Züchter
				</div>
				<div class='grow border border-gray-600 px-4'>
					Datum / Unterschrift Obmann
				</div>
				<div class='w-64 my-4 flex flex-col font-bold text-3xl'>
					<div class='text-center'>Leistungsnote</div>
					<strong class='m-4 text-center'>{grade(pair.grade)}</strong>
				</div>
			</footer>
		{/if}


	</Form>

</main>

<style>

</style>