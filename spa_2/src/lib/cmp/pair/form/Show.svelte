<script>
	import {onMount} from 'svelte';
	import {fade, slide} from 'svelte/transition';
	import { dec } from '$lib/js/toolbox.js';
	import { DateInput, NumberInput, RingInput, Select, TextInput, validator } from '../../form/Form.svelte';

	let { pair=$bindable() } = $props();

	let count = $state( null );
	let avg = $state( null );

	let labels = [ 'U/o', 90, 91, 92, 93, 94, 95, 96, 97 ];

	const validate = {
		score:       v => validator(v).number().range( 0, 999 ).orNull().isValid(),
	}

	$effect( () => {
		count      = pair.show.scores.reduce( ( sum, score ) => sum += score.count, 0 );
		const sum  = pair.show.scores.reduce( ( sum, score ) => sum += score.count*score.weight, 0 )
		pair.showGrade = count > 0 ?  sum / count : null;
	})

</script>


<fieldset class='border p-2' in:fade>
	<legend>Schauleistung</legend>
	{#if pair.colorId }
		<div class='flex flex-row gap-x-2' transition:slide>
			<div class='w-8'></div>
			{#each pair.show.scores as score, i }
				<NumberInput class='w-16' label={labels[i]} bind:value={ pair.show.scores[i].count } validator={validate.score} />
			{/each}

			<div class='grow'></div>
			<NumberInput class='w-14' label='Nummern' value={ count } disabled />
			<NumberInput class='w-14 font-bold' label='Note' value={ dec( pair.showGrade, 1 ) } disabled />
		</div>
	{/if}
</fieldset>