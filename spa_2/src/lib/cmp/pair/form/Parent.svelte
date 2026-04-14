<script>
	import {onMount} from 'svelte';
	import {fade, slide} from 'svelte/transition';
	import aab from '$lib/js/aab.js';
	import { cfg, ctx } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import {dec, swapHrefId} from '$lib/js/tools.js';
	import {validator, Label, NumberInput, RingInput, Select, TextInput } from '$lib/cmp/form/Form.svelte';
	import { toRing } from '$lib/cmp/form/validator.js';


	let { parent=$bindable(), pair, i } = $props();

	let parentPairs = $state( [] ); // the parent it's parent pair options
	let parentPair = $state( null ); // the selected parents pair
	let parentLayGrade = $state( null ); // only used for layers
	let parentBroodGrade = $state( null );
	let parentShowGrade = $state( null );
	let parentTotalGrade = $state( null );
	//let totalGrade = $state( null );
	let ringYear = null; //toRing( parent.ring ).year; // to track year change for parentPair select

	const validate = {
		ring:      (v) => validator(v).ring().orNull().isValid(),
		score:     (v) => validator(v).number().range( 89, 97 ).orNull().isValid(),
	}

	// should the pair hold the parentspair object
	async function onRingBlur() {
		let ring = toRing( parent.ring ); // decode input to ring object
		if( ring ) { // valid ring
			if( pair.sectionId === cfg.pigeons ) { // pigeons
				let pairs = await model.Pair.query( { chick:ring.name } );
				if( pairs && pairs.length > 0 ) {
					parentPair = pairs[0];
					parent.parentsPairId = parentPair.id;
				} else {
					parentPair = null;
					parent.parentsPairId = null;
				}
			} else { // layers
				if( ring.year !== ringYear ) { // avoid re-requesting list of possible parents
					parentPairs = await model.Pair.query({breeder: pair.breeder.id, breed:pair.breedId, year:ring.year});

					if( ! parentPairs.find( pair => pair.id === parent.parentsPairId ) ) { // not in list
						parentPair = null;
						parent.parentsPairId = null;
					}
					ringYear = ring.year;
				}
			}
		} else {
			ringYear = null;
			parentPairs = [];
			parentPair = null;
			parent.parentsPairId = null;
		}
	}

	$effect( () => {
		if( pair.sectionId === cfg.pigeons ) {
			// parentPair already set by onBlur !
		} else {
			parentPair = parentPairs.find( pair => pair.id === parent.parentsPairId );
		}
		parentLayGrade = parentPair && parentPair.lay ? aab.lay(parentPair.lay.eggs, parentPair.lay.eggsShould) : null;
		parentBroodGrade = parentPair ?
			pair.sectionId === cfg.pigeons
				? aab.brood.pigeon(pair.breed.broodGroup, parentPair.brood.eggs/2, parentPair.brood.hatched )
				: aab.brood.layer(parentPair.brood.eggs, parentPair.brood.hatched)
			: null;
		parentShowGrade = parentPair ? parentPair.show.score : null;
		parent.grade = parentTotalGrade = pair.sectionId === cfg.pigeons
			? parentBroodGrade > 0 && parentShowGrade > 0 ?
				( parentBroodGrade + parentShowGrade ) / 2
				: null
			: parentLayGrade && parentBroodGrade && parentShowGrade ?
				( parentLayGrade + parentBroodGrade + parentShowGrade ) / 3
				: null
	});

	onMount( () => {
		onRingBlur();
	})

</script>

<section class='w-full flex flex-row gap-x-2 items-center'>
	<TextInput class='w-8 px-0 border-0' label={i===0?' #':null} value={i+1} disabled />

	{#if pair.sectionId === cfg.pigeons}
		<TextInput class='w-12' label={i===0?'♂.♀':null} value={parent.sex} disabled />
	{:else}
		<Select class='w-16' label={i===0?'♂.♀':null}  bind:value={parent.sex} >
			<option value='1.0'>1.0</option><option value='0.1'>0.1</option>
		</Select>
	{/if}
	<RingInput label={i===0?'Ring':null} bind:value={parent.ring} onblur={onRingBlur} validator={validate.ring}/>
	<NumberInput class='w-16' label={i===0?'Bewertung':null} bind:value={parent.score} step={0.1} validator={validate.score}/>
	<div class='w-4'></div>
	{#if pair.sectionId === cfg.pigeons}
		<TextInput class='w-32' label={i===0?'Aus Stamm':null} value={parentPair?(parentPair.year%100)+'.'+parentPair.name:null} title='Eltern Stamm aus Kükenring' disabled />
	{:else}
		<Select class='w-32' label={i===0?'Aus Stamm':null} bind:value={parent.parentsPairId} title={parentPairs.length > 0 ? `Wähle den Elternstamm` : 'Keine eingegebene Elternstämme'} disabled={ parentPairs.length === 0 }>
			<option value={null} ></option>
			{#each parentPairs as parentPair, i}
				<option value={parentPair.id} >{(parentPair.year%100)+'.'+parentPair.name}</option>
			{/each}
		</Select>
	{/if}
	{#if parent.parentsPairId}
		<a href={ swapHrefId( parent.parentsPairId )} title='Zum Elternpaar'>◎</a>
	{/if}

	{#if parentPair}
		<div class='grow flex flex-row gap-x-1 justify-end' in:fade>
			<Label label={i===0}>Abstammung : </Label>
			{#if pair.sectionId !== cfg.pigeons}
				<NumberInput class='w-16' label={i===0?'Legeleistung':null} title='Von dem Elternstamm' value={ dec( parentLayGrade, 1 ) } disabled/>
			{/if}
			<NumberInput class='w-16' label={i===0?'Brutleistung':null} title='Von dem Elternstamm' value={ dec( parentBroodGrade, 1 ) } disabled/>
			<NumberInput class='w-16' label={i===0?'Schauleistung':null} title='Von dem Elternstamm' value={ dec( parentShowGrade, 1 ) } disabled/>
			<Label label={i===0}> ⇒ </Label>
			<NumberInput class='w-16 font-bold' label={i===0?'Note':null}  value={ dec( parentTotalGrade, 1 ) } title='Gesamtbewertung mit Abstammung' disabled />
		</div>
	{/if}

</section>

<style>
</style>
