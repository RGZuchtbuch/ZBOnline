<script>
	import { slide } from 'svelte/transition';
    import { meta } from 'tinro';
	import grading from '../../js/aab.js';
	import {dec} from '../../js/util.js';
	import { standard } from '../../js/store.js'
	import validator from '../../js/validator.js';

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
						{ sex:'1.0', ring: null, brood: { count:null, eggs: null, hatched: null, grade: '?'}, grade: '?'},
						{ sex:'0.1', ring: null, lay: {eggs: null, grade: '?'}, brood: { count:null, eggs: null, hatched: null, grade: '?'}, grade: '?'},
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
		if( pair.breed && pair.breed.layEggs > 0 && lay && lay.eggs > 0 ) {
			const grade = grading.lay(lay.eggs, pair.breed.layEggs);
			lay.grade = grade === null ? '?' : grade;
		}
		recalculateGrades();
	}

	function gradeBrood( brood ) {
		if( pair.section ) {
			if( pair.section.id === PIGEONS ) {
				if( brood && brood.count > 0 && brood.hatched !== null ) {
					const grade = grading.brood.pigeons( pair.breed.broodGroup, brood.count, brood.hatched );
					brood.grade = grade === null ? '?' : grade;
				} else {
					brood.grade = '?';
				}
			} else {
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
		recalculateGrades();
	}

	function recalculateGrades() {
		console.log( 'grade pair' );

		if( pair.section && pair.breed ) {
			for( const animal of pair.animals ) {
				for( const parent of animal.parents ) {
					if( pair.section.id !== PIGEONS && parent.sex === '0.1') {
						if( parent.brood.grade === 0 || parent.lay.grade === 0 ) {
							parent.grade = 0;
						} else if( parent.brood.grade === '?' || parent.lay.grade === '?' ) {
							parent.grade = '?';
						} else {
							parent.grade = ( parent.brood.grade + parent.lay.grade ) / 2.0
						}
					} else {
                        parent.grade = parent.brood.grade;
					}
				}
				if( animal.parents.some( (parent) => parent.grade === 0 ) ) {
					animal.grade = 0;
				} else if( animal.parents.some( (parent) => parent.grade === '?' ) ) {
					animal.grade = '?';
				} else {
					animal.grade = animal.parents.reduce((sum, current) => sum + current.grade, 0) / animal.parents.length;
				}
			}

			if( pair.animals.some( ( animal ) => animal.grade === 0 ) ) {
				pair.grade = 0;
			} else if ( pair.animals.some( ( animal ) => animal.grade === '?' ) ) {
				pair.grade = '?';
			} else {
				pair.grade = pair.animals.reduce( ( sum, current ) => sum + current.grade, 0 ) / pair.animals.length;
			}
		} else {
			pair.grade = '?';
		}
	}

	function grade( value, dec = 1 ) {
		console.log( 'Grade', value );
		return value === 0 ? '0' : value === '?' ? '?' : value.toFixed( dec )+' p.';
	}

    createData();

</script>

<main class='w-256 p-4 bg-gray-100 text-xl transition:slide'>
	<Form autoSave={false}>
		<header>
			<h2 class='text-center'>BDRG Zuchtbuch</h2>

			<h1 class='flex flex-row gap-x-4 justify-center'>
				Abstammungsnachweis {pair.section ? pair.section.display :'?'}
			</h1>

			<fieldset class='flex flex-row gap-x-2'>
				<span class='w-40 mt-6'> Ausstellung am </span>
				<DateInput label='Datum'/>
				<span class='w-8 mt-6 text-center'> in </span>
				<TextInput class='grow' label='Schau'/>
			</fieldset>
		</header>

		<main>
			<fieldset class='flex flex-row gap-x-2'>
				<span class='w-40 mt-6'> Züchter </span>
				<TextInput class='grow' label='Züchter'/><TextInput class='w-32' label='Zuchtbuch-Nr' />
			</fieldset>
			<fieldset class='flex flex-col'>
				<div class='flex flex-row gap-x-2 no-print'>
					<span class='w-40 mt-6'> Sparte </span>
					<Select class='w-96' label='Sparte *' bind:value={pair.section} error='Pflichtfeld' on:change={updateBreeds}>
						<option value={null}>?</option>
						{#each sections as section }
							<option value={section}>{section.name}</option>
						{/each}
					</Select>
				</div>
				<div class='flex flex-row gap-x-2'>
					<span class='w-40 mt-6'> Rasse </span>
					<Select class='w-96' label={'Rasse *'} bind:value={pair.breed} error='Pflichtfeld' on:change={clearResults}>
						<option value={null}>?</option>
						{#if breeds}
							{#each breeds as breed }
								<option value={breed}>{breed.name}</option>
							{/each}
						{/if}
					</Select>

					<Select class='grow' label={'Farbenschlag'} bind:value={pair.color} error='Pflichtfeld'>
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
			<div class='flex flex-col gap-y-2 mt-4' transition:slide>
				<div class='flex flex-row'>
					<div class='w-64 mx-4'>
						Stamm
					</div>
					<div class='grow'>
						Elterntiere : Brutgruppe {pair.breed.broodGroup}
					</div>
				</div>

				{#each pair.animals as animal }
					<fieldset class='flex flex-col gap-y-2 border border-gray-400 rounded p-1'>
						<div class='flex flex-row'>
							<div class='flex flex-col'>
								<div class='flex flex-row p-4'>
									<span class='w-8 mt-6 mx-2'>{animal.sex}</span>
									<RingInput class='w-36' label='Bundesring {animal.sex}'/>
								</div>
								<output class='text-2xl text-center font-bold'>{ grade( animal.grade, 1 ) }</output>
							</div>

							<div class='grow lex flex-col gap-y-1'>
								{#each animal.parents as parent}
									{#if pair.section.id === PIGEONS}
										<div class='flex flex-row border border-gray-400 rounded p-2'>
											<span class='w-8 my-6 mx-1'>{parent.sex}</span>
											<RingInput class='w-36' label='Bundesring {parent.sex}'/>
											<fieldset class='grow flex flex-row gap-x-2' on:input={ () => gradeBrood( parent.brood ) }>
												<span class='w-36 mt-6 text-center'> → Brutleistung</span>
												<NumberInput class='w-24' label='Bruten' bind:value={parent.brood.count} validator={validate.pigeon.brood.count} />
												<span class='w-8 mt-6'>mit</span>
												<NumberInput class='w-32' label='Beringte Jungtauben' bind:value={parent.brood.hatched} validator={validate.pigeon.brood.hatched( parent.brood )}  />
												<span class='w-4 mt-6'>=</span>
												<output class='grow mt-6 mx-1 text-xl font-bold text-center'>{ grade( parent.brood.grade )}</output>
											</fieldset>
										</div>
									{:else}
										<div class='flex flex-row border border-gray-400 rounded p-2'> <!-- parent -->
											<div class='flex flex-col'>
												<div class='flex flex-row'>
													<span class='w-8 my-6 mx-1'>{parent.sex}</span>
													<RingInput class='w-36' label='Bundesring {parent.sex}'/>
												</div>
												{#if parent.sex === '0.1'}
													<div class='text-center font-bold'>{ grade( parent.grade )}</div>
												{/if}
											</div>

											<div class='flex flex-col'>
												{#if parent.sex === '0.1'}
													<fieldset class='flex flex-row' on:input={ () => gradeLay( parent.lay ) }>
														<span class='w-36 mt-6 mx-1 text-center'> → Legeleistung</span>
														<NumberInput class='w-32' label='Legeleistung' bind:value={ parent.lay.eggs } validator={validate.layer.lay.eggs}/>
														<span class='w-8 mt-6 mx-1 text-center'>und</span>
														<NumberInput class='w-32' label='SOLL Legeleistuing' value={pair.breed ? pair.breed.layEggs : '?'} disabled />
														<span class='w-4 mt-6 mx-1 text-center'>=</span>
														<output class='grow mt-6 mx-1 text-xl font-bold text-center'>{ grade( parent.lay.grade) }</output>
													</fieldset>
												{/if}

												<fieldset class='flex flex-row ' on:input={ () => gradeBrood( parent.brood ) }>
													<span class='w-36 mt-6 mx-1'>→ Brutleistung</span>
													<NumberInput class='w-32' label='Eingelegte Eier' bind:value={parent.brood.eggs} validator={ validate.layer.brood.eggs } />
													<span class='w-8 mt-6 mx-1'>mit</span>
													<NumberInput class='w-32' label='Geschüpfte Küken' bind:value={parent.brood.hatched} validator={ validate.layer.brood.hatched(parent.brood) } />
													<span class='w-4 mt-6 mx-1'>=</span>
													<output class='grow mt-6 mx-1 text-xl font-bold'>{ grade( parent.brood.grade )}</output>
												</fieldset>
											</div>
										</div>
									{/if}
								{/each}
							</div>
						</div>
					</fieldset>
				{/each}

				<footer class='flex flex-row align-stretch mt-2 gap-x-4'>
					<div class='flex grow border border-gray-600 px-4'>
						Datum / Unterschrift Züchter
					</div>
					<div class='grow border border-gray-600 px-4'>
						Datum / Unterschrift Obmann
					</div>
					<div class='w-64 my-4 flex flex-col font-bold text-3xl'>
						<div class='text-center'>Leistungsnote</div>
						<strong class='m-4 text-center'>{grade(pair.grade, 0)}</strong>
					</div>
				</footer>
			</div>
		{/if}


	</Form>

</main>

<style>

</style>