<script>
	import { slide } from 'svelte/transition';
//	import { meta } from 'tinro';
	import { cfg, ctx } from '$lib/js/store.svelte.js';
	import aab from '$lib/js/aab.js';

	import Form, { Select, NumberInput, validator } from '$lib/cmp/form/Form.svelte';

	const sections = ctx.standard.rootSections;

	let section = $state( null );
	let breed = $state( null );
	let lay   = $state( { eggs:null, grade:null } );
	let brood = $state( { count:null, eggs:null, hatched:null, grade:null } );

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

	function onBreedChanged() {
		// lay.eggs = lay.grade = null;
		// brood.count = brood.eggs = brood.hatched = brood.grade = null;
	}

	function onSectionChanged(event ) {
		console.log('Section changed' );
		if( section && section.id > 0 ) {
			breed = null;
			lay.eggs = lay.grade = null;
			brood.count = brood.eggs = brood.hatched = brood.grade = null;
		}
	}


	function gradeLay( lay ) {
		if( breed && breed.layEggs > 0 && lay && lay.eggs > 0 ) {
			const grade = aab.lay(lay.eggs, breed.layEggs);
			lay.grade = grade === null ? '?' : grade;
			lay = lay;
		}
	}

	function gradeBrood( brood ) {
		console.log( 'Calc brood' );
		if( section ) {
			if( section.id === cfg.pigeons ) {
				if( brood && brood.count > 0 && brood.hatched !== null ) {
					const grade = aab.brood.pigeon( breed.broodGroup, brood.count, brood.hatched );
					brood.grade = grade === null ? '?' : grade;
				} else {
					brood.grade = '?';
				}
			} else { // layers
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
	}


	function grade( value, dec = 0 ) {
		return value === null ? '?' : value === 0 ? '0' : value === '?' ? '?' : value.toFixed( dec );
	}

	$effect( () => {
		gradeLay( lay );
	} );
	$effect( () => {
		gradeBrood( brood );
	} );

</script>

<div>
	<div class='px-0'>
		<Form autoSave={false}>


			<section>
				<fieldset class='flex flex-col'>
					<h2>Wähle Sparte und Rasse</h2>
					<div class='flex flex-row gap-x-4'>
						<span class='hidden md:block w-16 mt-6'> Sparte </span>
						<Select class='w-72' label='Sparte *' bind:value={section} error='Pflichtfeld' onchange={onSectionChanged}>
							<option value={null}>?</option>
							{#each sections as section }
								<option value={section}>{section.name}</option>
							{/each}
						</Select>
					</div>
					<div class='flex flex-row gap-x-4'>
						<span class='hidden md:block w-16 mt-6'> Rasse </span>
						<Select class='w-72' label={'Rasse *'} bind:value={breed} error='Pflichtfeld' onchange={onBreedChanged}>
							<option value={null}>?</option>
							{#if section && section.breeds}
								{#each section.breeds as breed }
									<option value={breed}>{breed.name}</option>
								{/each}
							{/if}
						</Select>
					</div>
				</fieldset>
			</section>

			<hr>


			{#if section && breed}
				<div class='flex flex-col'>
					{#if section.id === cfg.pigeons}
						<div class='text-center'>Brutgruppe {breed.broodGroup }</div>
						<fieldset class='flex flex-row gap-x-2 justify-evenly'>
							<span class='hidden md:block w-40 mt-3 text-left'>Brutleistung</span>
							<NumberInput class='w-16 md:w-24' label='Bruten' bind:value={brood.count} validator={validate.pigeon.brood.count} />
							<span class='mt-3 text-center'>mit</span>
							<NumberInput class='w-16 md:w-24' label='Küken' bind:value={brood.hatched} validator={validate.pigeon.brood.hatched( brood )}  />
							<span class='w-2 mt-3 text-center'>⇒</span>
							<output class='w-8 mt-3 text-xl font-bold text-center'>{ grade( brood.grade )}</output>
						</fieldset>
					{:else}
						<fieldset class='flex flex-col md:flex-row gap-x-4'>
							<div class='w-40 mt-3 text-left'>Legeleistung</div>
							<div class='flex flex-row justify-evenly'>
								<NumberInput class='w-16 md:w-24' label='Legen e/j' bind:value={ lay.eggs } validator={validate.layer.lay.eggs}/>
								<div class='w-8 mt-3 text-center'>von</div>
								<NumberInput class='w-16 md:w-24' label='Soll' value={breed ? breed.layEggs : '?'} disabled />
								<div class='w-8 mt-3 text-center'>⇒</div>
								<output class='w-8 mt-3 text-xl font-bold text-center whitespace-nowrap'>{ grade( lay.grade ) }</output>
							</div>
						</fieldset>

						<fieldset class='flex flex-col md:flex-row p-4 gap-x-4'>
							<span class='w-40 mt-3 text-left'>Brutleistung</span>
							<div class='flex flex-row justify-evenly'>
								<NumberInput class='w-16 md:w-24' label='Eingelegt' bind:value={brood.eggs} validator={ validate.layer.brood.eggs } />
								<span class='w-8 mt-3 text-center'>mit</span>
								<NumberInput class='w-16 md:w-24' label='Geschüpft' bind:value={brood.hatched} validator={ validate.layer.brood.hatched(brood) } />
								<span class='w-8 mt-3 text-center'>⇒</span>
								<output class='w-8 mt-3 text-xl font-bold text-center whitespace-nowrap'>{ grade( brood.grade )}</output>
							</div>
						</fieldset>
					{/if}
				</div>
			{:else}
				<p class='m-16 text-center italic'>
					Der Leistungsdatenteil erscheint sobalt die Rasse ausgewählt worden ist.<br>
					Beachte: diese Daten werden nicht gespeichert !
				</p>
			{/if}
		</Form>
	</div>
</div>

<style>

</style>