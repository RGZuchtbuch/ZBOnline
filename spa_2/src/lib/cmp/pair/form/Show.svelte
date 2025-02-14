<script>
	import {fade, slide} from 'svelte/transition';
	import {calculateLay, dec, txt } from '$lib/js/toolbox.js';
	import Form, { DateInput, NumberInput, RingInput, Select, TextInput, validator } from '../../form/Form.svelte';
	//	import Form from '$lib/form/form/Profile.svelte';

	let { pair } = $props();

	$inspect( 'P', pair );

	let labels = [ 'U/o', 90, 91, 92, 93, 94, 95, 96, 97 ];

	const validate = {
		score:       v => validator(v).number().range( 0, 999 ).orNull().isValid(),
	}


	$effect( () => {
		//pair.show.count = Object.values( pair.show ).reduce( ( accu, score ) => accu+score, 0); // count all
		//pair.show.score = pair.show.count ? pair.count.scores.reduce( ( accu, score, index ) => accu+score*(89+index), 0 )/pair.show.count : null;
	});



</script>


<fieldset class='flex flex-row gap-x-2 border p-2' in:fade>
	<legend>Schauleistung</legend>
	{#each Object.entries(pair.show) as [ points, count ], i }
		<NumberInput class='w-16' label={labels[i]} bind:value={ pair.show[ points ]} validator={validate.score} />
	{/each}

	<div class='grow'></div>
	<NumberInput class='w-20' label='Nummern' value={ dec( pair.show.count ) } disabled />
	<NumberInput class='w-20' label='Leistungsnote' value={ dec( pair.show.score, 1 ) } disabled />
</fieldset>