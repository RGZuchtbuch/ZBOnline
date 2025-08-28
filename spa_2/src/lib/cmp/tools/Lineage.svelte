<script>
	import { slide } from 'svelte/transition';
	import aab from '$lib/js/aab.js';
	import { dat, dec } from '$lib/js/tools.js';
	import { cfg, ctx } from '$lib/js/store.svelte.js'

	import Form, { DateInput, NumberInput, RingInput, Select, TextInput, validator } from '$lib/cmp/form/Form.svelte';

	//const sections = [ { id:3, name:'Groß und Wassergeflügel', display:'Hühner' }, { id:11, name:'Große Hühner', display:'Hühner' }, { id:12, name:'Zwerghühner und Wachteln', display:'Hühner' }, { id:5, name:'Tauben', display:'Tauben' } ];
	const sections = ctx.standard.rootSections;
	let breeds = $state( null );
	let colors = $state( null );
	let pair = $state( null );

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
		pair = { // only generic data, rest depends on section : layers vs pigeons
			number: null,
			date: null, show: null,
			breeder: null,
			section: null, breed: null, color: null,
			parents : [],
			grade: '?'
		}
		clearResults();
	}

	function clearResults() {
		console.log( 'ClearResults');
		const parents = [];
		if( pair.section ) {
			const max = pair.section.id === cfg.pigeons ? 2 : 3;
			for (let i = 0; i < max; i++) {
				parents.push({
					sex: i === 0 ? '1.0' : '0.1',
					ring: null,
					grade: '?',
					parents: [ // grandparents
						{ sex:'1.0', ring: null, brood: { count:null, eggs: null, hatched: null, grade: '?'}, grade: '?'},
						{ sex:'0.1', ring: null, lay: {eggs: null, grade: '?'}, brood: { count:null, eggs: null, hatched: null, grade: '?'}, grade: '?'},
					],
				});
			}
		}
		pair.parents = parents;
		//console.log( 'Pair', pair );
	}

	function updateBreeds( event ) { // should do with rootSection.breeds
		console.log( 'UpdateBreeds' );
		// const section = pair.section;
		// if( section && section.id > 0 ) {
		// 	breeds = [];
		// 	const foundSection = findSection( section.id, ctx.standard );
		// 	if( foundSection ) {
		// 		collectBreeds(foundSection, breeds);
		// 		breeds.sort( (a, b) => a.name < b.name ? -1 : a.name > b.name ? 1 : 0 );
		// 	}
		// 	pair.breed = null;

		 	clearResults();
		// }

	}

	function onBreedChange( event ) {
		clearResults();
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
			const grade = aab.lay( lay.eggs, pair.breed.layEggs );
			lay.grade = grade === null ? '?' : grade;
		}
		recalculateGrades();
	}

	function gradeBrood( brood ) {
		if( pair.section ) {
			if( pair.section.id === cfg.pigeons ) {
				if( brood && brood.count > 0 && brood.hatched !== null ) {
					const grade = aab.brood.pigeon( pair.breed.broodGroup, brood.count, brood.hatched );
					brood.grade = grade === null ? '?' : grade;
				} else {
					brood.grade = '?';
				}
			} else {
				if( brood && brood.eggs > 0 && brood.hatched !== null ) {
					const grade = aab.brood.layer( brood.eggs, brood.hatched );
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
//const grade = aab.brood.pigeon( breed.broodGroup, brood.count, brood.hatched );

	function recalculateGrades() { // when updating any part of pair, could be optimized surely
		console.log( 'grade pair' );

		if( pair.section && pair.breed ) {
			for( const parent of pair.parents ) {
				for( const grandParent of parent.parents ) { // grandparents
					if( pair.section.id !== cfg.pigeons && grandParent.sex === '0.1') {
						console.log( 'Grading p 0.1' );
						if( grandParent.brood.grade === 0 || grandParent.lay.grade === 0 ) {
							grandParent.grade = 0;
						} else if( grandParent.brood.grade === '?' || grandParent.lay.grade === '?' ) {
							grandParent.grade = '?';
						} else {
							grandParent.grade = ( grandParent.brood.grade + grandParent.lay.grade ) / 2.0
						}
					} else {
						console.log( 'Grading 1.0' );
						grandParent.grade = grandParent.brood.grade;
					}
				}
				if( parent.parents.some( (parent) => parent.grade === 0 ) ) {
					parent.grade = 0;
				} else if( parent.parents.some( (parent) => parent.grade === '?' ) ) {
					parent.grade = '?';
				} else {
					parent.grade = parent.parents.reduce((sum, current) => sum + current.grade, 0) / parent.parents.length;
				}
			}

			if( pair.parents.some( ( parent ) => parent.grade === 0 ) ) {
				pair.grade = 0;
			} else if ( pair.parents.some( ( parent ) => parent.grade === '?' ) ) {
				pair.grade = '?';
			} else {
				pair.grade = pair.parents.reduce( ( sum, current ) => sum + current.grade, 0 ) / pair.parents.length;
			}
		} else {
			pair.grade = '?';
		}
	}

	function grade( value, dec = 1 ) {
		return value === 0 ? '0' : value === '?' ? '?' : value.toFixed( dec );
	}

	createData();

	$inspect( 'Pair', pair );

</script>

<main class='w-full p-4 bg-gray-100 text-xl transition:slide'>
	<Form autoSave={false}>
		<fieldset>
			<h1 class='flex flex-row gap-x-4 justify-center text-2xl'>
				BDRG Zuchtbuch, Abstammungsnachweis
			</h1>

			<fieldset class='border-0 flex flex-row px-2 gap-x-2'>
				<span class='w-40 mt-6'> Ausstellung am </span>
				<DateInput class='w-32' label='Datum'/>
				<span class='w-8 mt-6 text-center'> in </span>
				<TextInput class='grow' label='Schau'/>
				<TextInput class='w-24' label='Käfig-Nr.' />
			</fieldset>

			<fieldset class='border-0 flex flex-row px-2 gap-x-2'>
				<span class='w-40 mt-6'> Züchter </span>
				<TextInput class='grow' label='Züchter'/><TextInput class='w-32' label='Zuchtbuch-Nr' />
			</fieldset>
			<fieldset class='border-0 flex flex-col'>
				<div class='flex flex-row gap-x-2 px-2 print:hidden'>
					<span class='w-40 mt-6'> Sparte </span>
					<Select class='w-96' label='Sparte *' bind:value={pair.section} error='Pflichtfeld' onchange={clearResults}>
						<option value={null}>?</option>
						{#each ctx.standard.rootSections as section }
							<option value={section}>{section.name}</option>
						{/each}
					</Select>
				</div>
				<div class='flex flex-row px-2 gap-x-2'>
					<span class='w-40 mt-6'> Rasse </span>
					<Select class='w-96' label={'Rasse *'} bind:value={pair.breed} error='Pflichtfeld' onchange={clearResults}>
						<option value={null}>?</option>
						{#if pair.section}
							{#each pair.section.breeds as breed }
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
		</fieldset>

		{#if pair.breed}
			<div class='flex flex-col gap-y-2 mt-4' transition:slide>
				<div class='flex flex-row'>
					<div class='w-64 mx-4'>
						Stamm
					</div>
					<div class='grow'>
						Elterntiere
					</div>
					<div class='pr-4'>
						<span>Elterntiere Leistung</span>
						{#if pair && pair.section && pair.section.id === cfg.pigeons && pair.breed }, Brutgruppe {pair.breed.broodGroup} {/if}
					</div>
				</div>

				{#each pair.parents as parent }
					<fieldset class='flex flex-col gap-y-2 border border-gray-400 rounded p-1'>
						<div class='flex flex-row'>
							<div class='flex flex-col'>
								<div class='flex flex-row p-4'>
									<span class='w-8 mt-6 mx-2'>{parent.sex}</span>
									<RingInput class='w-36' label='Bundesring {parent.sex}'/>
								</div>
								<output class='text-2xl text-center font-bold'>{ grade( parent.grade ) }</output>
							</div>

							<div class='grow lex flex-col gap-y-1'>
								{#each parent.parents as grandParent}
									{#if pair.section.id === cfg.pigeons} <!-- Pigeons -->
										<div class='flex flex-row border border-gray-400 rounded p-2 justify-evenly'>
											<span class='w-8 my-6 mx-1'>{grandParent.sex}</span>
											<RingInput class='w-36' label='Bundesring {grandParent.sex}'/>
											<fieldset class='grow border-0 flex flex-row gap-x-2 justify-evenly' on:input={ () => gradeBrood( grandParent.brood ) }>
												<span class='w-36 mt-6 text-left'>→ Bruten</span>
												<NumberInput class='w-24' label='Bruten' bind:value={grandParent.brood.count} validator={validate.pigeon.brood.count} />
												<span class='w-8 mt-6'>mit</span>
												<NumberInput class='w-32' label='Beringte Jungtauben' bind:value={grandParent.brood.hatched} validator={validate.pigeon.brood.hatched( grandParent.brood )}  />
												<span class='w-4 mt-6'>→</span>
												<output class='w-8 mt-6 mx-1 text-xl font-bold text-center'>{ grade( grandParent.brood.grade )}</output>
											</fieldset>
										</div>
									{:else} <!-- Layers -->
										<div class='flex flex-row border border-gray-400 rounded p-2 justify-evenly'> <!-- parent -->
											<div class='flex flex-col'>
												<div class='flex flex-row'>
													<span class='w-8 my-6 mx-1'>{grandParent.sex}</span>
													<RingInput class='w-36' label='Bundesring {grandParent.sex}'/>
												</div>
												{#if false && grandParent.sex === '0.1'}
													<div class='text-center font-bold'>{ grade( grandParent.grade )}</div>
												{/if}
											</div>

											<div class='grow flex flex-col'>
												{#if grandParent.sex === '0.1'}
													<fieldset class='grow border-0 flex flex-row justify-evenly' on:input={ () => gradeLay( grandParent.lay ) }>
														<div class='w-32 mt-6 mx-1 text-left'>→ Legen</div>
														<NumberInput class='w-32' label='Legen e/j' bind:value={ grandParent.lay.eggs } validator={validate.layer.lay.eggs}/>
														<div class='w-8 mt-6 mx-1 text-center'>von</div>
														<NumberInput class='w-32' label='SOLL Legen' value={pair.breed ? pair.breed.layEggs : '?'} disabled />
														<div class='w-4 mt-6 mx-1 text-center'>→</div>
														<output class='w-8 mt-6 mx-1 text-xl font-bold text-center'>{ grade( grandParent.lay.grade) }</output>
													</fieldset>
												{/if}

												<fieldset class='grow border-0 flex flex-row justify-evenly' on:input={ () => gradeBrood( grandParent.brood ) }>
													<span class='w-32 mt-6 mx-1 text-left'>→ Brut</span>
													<NumberInput class='w-32' label='Eingelegt' bind:value={grandParent.brood.eggs} validator={ validate.layer.brood.eggs } />
													<span class='w-8 mt-6 mx-1 text-center'>mit</span>
													<NumberInput class='w-32' label='Geschlüpft' bind:value={grandParent.brood.hatched} validator={ validate.layer.brood.hatched(grandParent.brood) } />
													<span class='w-4 mt-6 mx-1 text-center'>→</span>
													<output class='w-8 mt-6 mx-1 text-xl font-bold text-center'>{ grade( grandParent.brood.grade )}</output>
												</fieldset>

												{#if false && grandParent.sex === '0.1'}
													<div class='grow border-black border-t rounded-none flex flex-row justify-end'>
														<output class='w-8 mt-2 mx-1 text-xl font-bold text-center'>{ grade( grandParent.grade) }</output>
													</div>
												{/if}
											</div>
										</div>
									{/if}
								{/each}
							</div>
						</div>
					</fieldset>
				{/each}

				<footer class='flex flex-row align-stretch mt-2 h-32 gap-x-4'>
					<div class='flex grow border border-gray-600 px-4'>
						Datum / Unterschrift Züchter
					</div>
					<div class='grow border border-gray-600 px-4'>
						Datum / Unterschrift Obmann
					</div>
					<div class='w-64 my-1 flex flex-col font-bold text-3xl'>
						<div class='text-center'>Leistungsnote</div>
						<strong class='m-2 text-center'>{grade(pair.grade, 0)}</strong>
					</div>
				</footer>
			</div>
		{:else}
			<div class='text-center italic'>Der Leistungsdatenteil erscheint sobalt die Rasse eingegeben ist</div>
		{/if}


	</Form>

</main>

<style>

</style>