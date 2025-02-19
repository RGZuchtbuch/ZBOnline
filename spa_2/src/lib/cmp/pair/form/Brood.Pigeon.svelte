<script>
	import {fade, slide} from 'svelte/transition';
	import aab from '$lib/js/aab.js';
	import { dec, txt } from '$lib/js/toolbox.js';
	import Form, { DateInput, NumberInput, RingInput, Select, TextInput, validator } from '../../form/Form.svelte';
	//	import Form from '$lib/form/form/Profile.svelte';

	let { brood=$bindable(), pair, standard, i } = $props();
	brood.eggs = 2; // default 2 eggs per brood for pigeons

	const validate = {
		start:      v => validator(v).date().orNull().isValid(),
		//eggs:       v => validator(v).number().range( 1, 99999 ).orNull().isValid(),
		//fertile:    v => validator(v).number().range( 0, pair.brood.eggs ).orNullIf( pair.brood.eggs === null ).isValid(),
		hatched:    v => validator(v).number().range( 0, 2 ).orNull().isValid(),
	}

	$effect( () => {
		// I do not calculate as theres only 1 brood, so no valid grade
		//brood.grade = aab.brood.pigeon( pair.breed.broodGroup, 12, 12*brood.hatched ); //dcks pair.brood.eggs ? 100 * pair.brood.hatched / pair.brood.eggs : null; // TODO
	} );

</script>


<div class='w-full flex flex-row gap-x-2' in:fade>
	<TextInput class='w-8' label={i===0?'#':null} value={i+1} disabled />
	<DateInput class='w-24' label='Gelegt am' bind:value={brood.start} validator={validate.start} />
	<NumberInput class='w-14' label='Eier' value={brood.eggs} disabled />
	<NumberInput class='w-14' label='Geschlüpft' bind:value={brood.hatched} validator={validate.hatched} />
	Pigeon
</div>