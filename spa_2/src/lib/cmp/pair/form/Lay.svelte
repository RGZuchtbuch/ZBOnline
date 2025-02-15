<script>
	import {fade, slide} from 'svelte/transition';
	import {calculateLay, daysBetween, dec, txt } from '$lib/js/toolbox.js';
	import Form, { DateInput, NumberInput, validator } from '../../form/Form.svelte';
//	import Form from '$lib/form/form/Profile.svelte';

	let { pair } = $props();


	console.log( 'Pair', pair );


	let days = $state( null );
	let result = $state( null );

	const validate = {
		start:      v => validator(v).date().orNullIf( pair.lay.end === null ).isValid(),
		end:        v => validator(v).date().after( pair.lay.start ).orNullIf( pair.lay.start === null ).isValid(),
		dames:      v => validator(v).number().range( 1,   99 ).orNullIf( pair.lay.end === null ).isValid(),
		total:      v => validator(v).number().range( 0, 9999 ).orNullIf( pair.lay.end === null ).isValid(),
		eggs:       v => validator(v).number().range( 0, 399 ).orNull().isValid(),
	}

	function onForward() { // copy result to oair
		pair.lay.eggs = result;//calculateLay( start, end, dames, eggs );
		console.log( 'E', pair.lay.eggs );
	}

	$effect( () => {
		days = daysBetween( pair.lay.start, pair.lay.end );
		result = calculateLay( pair.lay.start, pair.lay.end, pair.lay.dames, pair.lay.eggs );
		console.log('calc', result);
	})
	function updateScore() {
		pair.lay.score = pair.lay.eggs * 2; //TODO calculation from table
	}

	//$: console.log( 'V', start, end, dames, eggs)

	//$: updateInput( start, end, dames, eggs );
	//$: updateScore( pair.lay.eggs );


</script>


<fieldset class='flex flex-col gap-x-2 border p-2' in:fade>
	<legend>Legeleistung</legend>
	<div>Legeleistung im Jahr</div>
	<div class='flex flex-row gap-x-2'>
		<DateInput class='' label='Gesammelt ab' bind:value={pair.lay.start} validator={validate.start} />
		<DateInput class='' label='bis' bind:value={pair.lay.end} validator={validate.end} />
		<NumberInput class='w-16' label='Hennen' bind:value={pair.lay.dames} validator={validate.dames} />
		<NumberInput class='w-16' label='Total Eggs' bind:value={pair.lay.eggs} validator={validate.total} />
		<div class='w-8'></div>
		<NumberInput class='w-16' label='Ø Gewicht' bind:value={ pair.lay.weight }/>
		<div class='grow' />
		<NumberInput class='w-16' label='Tagen' value={ days } disabled/>
		<NumberInput class='w-16' label='Legeleistung' value={ result } disabled/>
		<NumberInput class='w-20' label='Leistungsnote' value={dec( 93.2 )} disabled/>
	</div>

</fieldset>