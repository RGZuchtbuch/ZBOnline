<script>
	import {onMount} from 'svelte';
	import {fade, slide} from 'svelte/transition';
	import { dec } from '$lib/js/toolbox.js';
	import { DateInput, NumberInput, RingInput, Select, TextInput, validator } from '../../form/Form.svelte';

	let { pair } = $props();

	let count = $state( null );
	let avg = $state( null );

	let labels = [ 'U/o', 90, 91, 92, 93, 94, 95, 96, 97 ];

	const validate = {
		score:       v => validator(v).number().range( 0, 999 ).orNull().isValid(),
	}

	$effect( () => {
		pair.show.count = pair.show.scores.reduce( ( sum, score ) => sum += score.count, 0 );
		pair.showGrade = pair.show.count > 0 ? pair.show.scores.reduce( ( sum, score ) => sum += score.count*score.weight, 0 ) / pair.show.count : null;
	})

</script>


<fieldset class='flex flex-row gap-x-2 border p-2' in:fade>
	<legend>Schauleistung</legend>
	{#each pair.show.scores as score, i }
		<NumberInput class='w-16' label={labels[i]} bind:value={ score.count } validator={validate.score} />
	{/each}

	<div class='grow'></div>
	<NumberInput class='w-20' label='Nummern' value={ pair.show.count } disabled />
	<NumberInput class='w-20' label='Leistungsnote' value={ dec( pair.showGrade, 1 ) } disabled />
</fieldset>