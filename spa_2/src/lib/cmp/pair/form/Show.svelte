<script>
	import {onMount} from 'svelte';
	import {fade, slide} from 'svelte/transition';
	import { dec } from '$lib/js/tools.js';
	import { DateInput, NumberInput, RingInput, Select, Status, TextInput, validator } from '../../form/Form.svelte';

	let { pair } = $props();

	let count = $state( null );
	let average = $state( null );

	let labels = [ 'U/o', 90, 91, 92, 93, 94, 95, 96, 97 ];

	const validate = {
		score:       v => validator(v).number().range( 0, 999 ).orNull().isValid(),
	}

	$effect( () => {
		let counter = 0;
		let total = 0;
		for( const key in pair.show.scores ) {
			counter += pair.show.scores[ key ];
			total += key * pair.show.scores[ key ];
		}
		count = counter;
		pair.showGrade = average = counter > 0 ? total / counter : null;
	})

</script>


<fieldset class='border p-2' in:fade>
	<legend>Schauleistung <Status /></legend>
	{#if true }
		<div class='flex flex-row gap-x-2' transition:slide>
			<div class='w-0'></div>
			{#each [89,90,91,92,93,94,95,96,97] as points, i}
				<NumberInput class='w-16' label={labels[i]} bind:value={ pair.show.scores[points] } validator={validate.score} />
			{/each}

			<div class='grow'></div>
			<NumberInput class='w-14' label='Nummern' value={ count } disabled />
			<NumberInput class='w-14 font-bold' label='Note' value={ dec( average, 1 ) } disabled />
		</div>
	{/if}
</fieldset>