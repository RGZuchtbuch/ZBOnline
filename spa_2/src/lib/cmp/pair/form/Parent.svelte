<script>
	import {onMount} from 'svelte';
	import {fade, slide} from 'svelte/transition';
	import aab from '$lib/js/aab.js';
	import api from '$lib/js/api.js.obs';
	import { dec } from '$lib/js/toolbox.js';
	import {validator, NumberInput, RingInput, Select, TextInput } from '$lib/cmp/form/Form.svelte';
	import { toRing } from '$lib/cmp/form/validator.js';


	let { parent=$bindable(), pair, standard, i } = $props();

	let fromPairs = $state( [] ); // the parent it's parent pair options
	let fromPair = $state( null );
	let fromLayGrade = $state( null );
	let fromBroodGrade = $state( null );
	let fromShowGrade = $state( null );
	let fromTotalGrade = $state( null );
	let ring = null; //toRing(parent.ring); // to detect year change


	const validate = {
		ring:      (v) => validator(v).ring().orNull().isValid(),
		score:     (v) => validator(v).number().range( 89, 97 ).orNull().isValid(),
	}

	// should the pair hold the parentspair object
	async function onRingInput() {
		let newRing = toRing( parent.ring ); // decode input
		if( newRing && ( ring === null || newRing.year !== ring.year ) ) {
			let response = await api.pair.get( { breederId:pair.breeder.id, year:newRing.year } );
			fromPairs = response.pairs;
			if( ring !== null ) parent.parentsPairId = null;
			ring = newRing;
		}
	}

	$effect( () => {
		fromPair = fromPairs.find( (pair) => pair.id === parent.parentsPairId );
		fromLayGrade   = fromPair ? aab.lay( fromPair.lay.eggs, fromPair.lay.eggsShould ) : null;
		fromBroodGrade = fromPair ? pair.sectionId === 5 ? aab.brood.pigeon( fromPair.brood.group, ) : aab.brood.layer( fromPair.brood.eggs, fromPair.brood.hatched ) : null;
		fromShowGrade  = fromPair ? fromPair.show.score : null;
	});

	$effect( () => {
		let count = (parent.score ? 1 : 0) + (fromLayGrade ? 1 : 0) + (fromBroodGrade ? 1 : 0);
		let sum   = parent.score + fromLayGrade + fromBroodGrade; //fromShowGrade;
		fromTotalGrade = count > 0 ? sum / count : null;
		parent.grade = fromTotalGrade;
	});

	onMount( () => {
		onRingInput();
	})

</script>

<div class='w-full flex flex-row gap-x-2 items-center'>
	<TextInput class='w-8 px-0 border-0' label={i===0?' #':null} value={i+1} disabled />
	<TextInput class='w-12' label={i===0?'♂.♀':null} value={parent.sex} disabled />
	<RingInput label={i===0?'Ring':null} bind:value={parent.ring} oninput={onRingInput} validator={validate.ring}/>
	<NumberInput class='w-16' label={i===0?'Bewertung':null} bind:value={parent.score} validator={validate.score}/>
	<div class='w-4'></div>
	<Select class='w-32' label={i===0?'Aus Stamm':null} bind:value={parent.parentsPairId}>
		{#each fromPairs as fromPair}
			<option value={fromPair.id} >{(fromPair.year%100)+'.'+fromPair.name}</option>
		{/each}
	</Select>
	<div class='grow'></div>
	<NumberInput class='w-16' label={i===0?'Legeleistung':null} title='Von dem Elternstamm' value={ dec( fromLayGrade, 1 ) } disabled/>
	<NumberInput class='w-16' label={i===0?'Brutleistung':null} title='Von dem Elternstamm' value={ dec( fromBroodGrade, 1 ) } disabled/>
	<div class='w-4'></div>
	<NumberInput class='w-14 font-bold' label={i===0?'G.Note':null} value={ dec( fromTotalGrade, 1 ) } disabled
        title='Schauleistung des Tiers und Lege u. Brutleisting der Eltern'
	/>
</div>
