<script>
	import {fade, slide} from 'svelte/transition';
	import {calculateLay, dec, txt } from '$lib/js/toolbox.js';
	import Form, { DateInput, NumberInput, RingInput, Select, TextInput, validator } from '../../form/Form.svelte';
	//	import Form from '$lib/form/form/Profile.svelte';

	let { pair } = $props();



	const validate = {
		eggs:       v => validator(v).number().range( 1, 99999 ).orNull().isValid(),
		fertile:    v => validator(v).number().range( 0, pair.brood.eggs ).orNullIf( pair.brood.eggs === null ).isValid(),
		hatched:    v => validator(v).number().range( 0, pair.brood.fertile ).orNullIf( pair.brood.fertile === null ).isValid(),
	}



	$effect( () => {
		pair.brood.score = pair.brood.eggs ? 100 * pair.brood.hatched / pair.brood.eggs : null; // TODO
	} );

</script>


<div in:fade>
	<NumberInput class='w-24' label='Eingelegt' bind:value={pair.brood.eggs} validator={validate.eggs} />
	<NumberInput class='w-24' label='Befruchtet' bind:value={pair.brood.fertile} validator={validate.fertile} />
	<NumberInput class='w-24' label='Geschlüpft' bind:value={pair.brood.hatched} validator={validate.hatched} />
	<div class='grow' />
	<TextInput class='w-24' label='Leistungsnote *' value={ dec( pair.brood.score, 1 ) } disabled />
</div>