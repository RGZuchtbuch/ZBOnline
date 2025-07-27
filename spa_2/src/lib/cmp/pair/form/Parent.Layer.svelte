<script>
	import {onMount} from 'svelte';
	import {fade, slide} from 'svelte/transition';
	import aab from '$lib/js/aab.js';
	import { ctx } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import { dec } from '$lib/js/tools.js';
	import { Label, NumberInput, RingInput, Select, TextInput, validator } from '$lib/cmp/form/Form.svelte';
	import { toRing } from '$lib/cmp/form/validator.js';


	let { parent=$bindable(), pair, i } = $props();

	let parentPairs = $state( [] ); // the parent it's parent pair options
	let parentPair = $state( null ); // the parents pair
	let parentLayGrade = $state( null );
	let parentBroodGrade = $state( null );
	let parentShowGrade = $state( null );
	let parentTotalGrade = $state( null );
	//let totalGrade = $state( null );
	let ringYear = null; // to track year change for parentPair select


	const validate = {
		ring:      (v) => validator(v).ring().orNull().isValid(),
		score:     (v) => validator(v).number().range( 89, 97 ).orNull().isValid(),
	}

	// should the pair hold the parentspair object
	async function onRingInput() {
		let newRing = toRing( parent.ring ); // decode input
		if( newRing ) {
			if( newRing.year !== ringYear ) {
				parentPairs = await model.Pair.query({breeder: pair.breeder.id, year: newRing.year});
				if (!parentPairs.find(pair => pair.id === parent.parentsPairId)) { // not in list
					parentPair = null;
					parent.parentsPairId = null;
				}
				ringYear = newRing.year;
			}
		} else {
			ringYear = null;
			parentPairs = [];
			parentPair = null;
			parent.parentsPairId = null;
		}
	}

	$effect( () => {
		parentPair = parentPairs.find( pair => pair.id === parent.parentsPairId );
		parentLayGrade = parentPair ? aab.lay(parentPair.lay.eggs, parentPair.lay.eggsShould) : null;
		parentBroodGrade = parentPair ? aab.brood.layer(parentPair.brood.eggs, parentPair.brood.hatched) : null;
		parentShowGrade = parentPair ? parentPair.show.score : null;
		parent.grade = parentTotalGrade = parentLayGrade && parentBroodGrade && parentShowGrade ? ( parentLayGrade + parentBroodGrade + parentShowGrade ) / 3: null;
	});

	onMount( () => {
		onRingInput();
	})

</script>

<div class='w-full flex flex-row gap-x-2 items-center'>
	<TextInput class='w-8 px-0 border-0' label={i===0?' #':null} value={i+1} disabled />
	<TextInput class='w-12' label={i===0?'♂.♀':null} value={parent.sex} disabled />
	<RingInput label={i===0?'Ring':null} bind:value={parent.ring} oninput={onRingInput} validator={validate.ring}/>
	<NumberInput class='w-16' label={i===0?'Bewertung':null} bind:value={parent.score} step={0.1} validator={validate.score}/>
	<div class='w-4'></div>
	<Select class='w-32' label={i===0?'Aus Stamm':null} bind:value={parent.parentsPairId} disabled={ parentPairs.length === 0 }>
		<option value={null} ></option>
		{#each parentPairs as parentPair}
			<option value={parentPair.id} >{(parentPair.year%100)+'.'+parentPair.name}</option>
		{/each}
	</Select>

	{#if parentPair}
		<div class='grow flex flex-row gap-x-1 justify-end' in:fade>
			<Label label={i===0}> + </Label>
			<NumberInput class='w-16' label={i===0?'Legeleistung':null} title='Von dem Elternstamm' value={ dec( parentLayGrade, 1 ) } disabled/>
			<NumberInput class='w-16' label={i===0?'Brutleistung':null} title='Von dem Elternstamm' value={ dec( parentBroodGrade, 1 ) } disabled/>
			<NumberInput class='w-16' label={i===0?'Schauleistung':null} title='Von dem Elternstamm' value={ dec( parentShowGrade, 1 ) } disabled/>
			<Label label={i===0}> = </Label>
			<NumberInput class='w-16 font-bold' label={i===0?'Note':null} value={ dec( parentTotalGrade, 1 ) } disabled
	             title='Durchschnitt Bewertung und Elternstamm Leistungen'
			/>
		</div>
	{/if}

</div>
