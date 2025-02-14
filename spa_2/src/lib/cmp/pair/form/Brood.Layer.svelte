<script>
	import {fade, slide} from 'svelte/transition';
	import {calculateLay, dec, txt } from '$lib/js/toolbox.js';
	import Form, { DateInput, NumberInput, RingInput, Select, TextInput, validator } from '../../form/Form.svelte';
	//	import Form from '$lib/form/form/Profile.svelte';

	let { brood } = $props();



	const validate = {
		eggs:       v => validator(v).number().range( 1, 99999 ).orNull().isValid(),
		fertile:    v => validator(v).number().range( 0, brood.eggs ).orNullIf( brood.eggs === null ).isValid(),
		hatched:    v => validator(v).number().range( 0, brood.fertile ).orNullIf( brood.fertile === null ).isValid(),
	}



	$effect( () => {
		brood.score = brood.eggs ? 100 * brood.hatched / brood.eggs : null; // TODO
	} );

</script>


<div class='w-full flex flex-row gap-x-2' in:fade>
	<NumberInput class='w-24' label='Eingelegt' bind:value={brood.eggs} validator={validate.eggs} />
	<NumberInput class='w-24' label='Befruchtet' bind:value={brood.fertile} validator={validate.fertile} />
	<NumberInput class='w-24' label='Geschlüpft' bind:value={brood.hatched} validator={validate.hatched} />
	<div class='grow'></div>
	<NumberInput class='w-20' label='Leistungsnote' value={dec( brood.score, 1 )} disabled/>
</div>