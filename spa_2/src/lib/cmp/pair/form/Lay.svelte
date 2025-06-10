<script>
	import {fade, slide} from 'svelte/transition';
	import aab from '$lib/js/aab.js';
	import {daysBetween, dec, txt } from '$lib/js/toolbox.js';
	import { DateInput, Label, NumberInput, TextInput, validator } from '../../form/Form.svelte';

	let { pair=$bindable(), standard } = $props();

	let days = $state( null );
	let result = $state( null );

	if( pair.lay === null ) { // only layers !
		pair.lay = { id:0, pairId:pair.id, start:null, end:null, dames:null, eggs:null, weight:null };
	}

	const validate = {
		start:      v => validator(v).date().orNullIf( pair.lay.end === null ).isValid(),
		end:        v => validator(v).date().after( pair.lay.start ).orNullIf( pair.lay.start === null ).isValid(),
		dames:      v => validator(v).number().range( 1,   99 ).orNullIf( pair.lay.end === null ).isValid(),
		eggs:       v => validator(v).number().range( 0, 9999 ).orNullIf( pair.lay.end === null ).isValid(),
		weight:     v => validator(v).number().range( 0,  399 ).orNull().isValid(),
	}

	$effect( () => {
		const breed = standard.breeds[ pair.breedId ];
		days = daysBetween( pair.lay.start, pair.lay.end );
		result = aab.layProduction( pair.lay.start, pair.lay.end, pair.lay.dames, pair.lay.eggs );
		pair.layGrade = breed ? aab.lay( result, breed.layEggs ) : null;
	})

</script>


<fieldset class='border p-2' in:fade>
	<legend>Legeleistung ({pair.layGrade}) {pair.sectionId}</legend>
	{#if pair.colorId && pair.sectionId !== 5 }
		<div class='flex flex-row gap-x-2' transition:slide>
			<div class='w-8'></div>
			<DateInput class='' label='Gesammelt ab' bind:value={pair.lay.start} validator={validate.start} />
			<DateInput class='' label='bis' bind:value={pair.lay.end} validator={validate.end} />
			<div class='w-4'></div>
			<NumberInput class='w-16' label='Hennen' bind:value={pair.lay.dames} validator={validate.dames} />
			<NumberInput class='w-16' label='# Eier' bind:value={pair.lay.eggs} validator={validate.eggs} />
			<div class='w-4'></div>
			<NumberInput class='w-20' label={`Gewicht ( ${pair.breed.layWeight} )`} bind:value={ pair.lay.weight } validator={validate.weight}/>
			<div class='grow' />
			<Label label={true}> → </Label>

			<NumberInput class='w-16' label='Tagen' value={ days } disabled/>
			<NumberInput class='w-20' label={`Legen ( ${pair.breed.layEggs} )`} value={ result } disabled/>
			<Label label={true}> = </Label>
			<NumberInput class='w-14 font-bold' label='Note' value={dec( pair.layGrade, 1 )} disabled/>
		</div>
	{/if}
</fieldset>