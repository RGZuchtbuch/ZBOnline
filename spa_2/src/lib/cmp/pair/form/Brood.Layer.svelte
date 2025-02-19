<script>
	import {fade, slide} from 'svelte/transition';
	import aab from '$lib/js/aab.js';
	import { dec, txt } from '$lib/js/toolbox.js';
	import Form, { DateInput, NumberInput, RingInput, Select, TextInput, validator } from '../../form/Form.svelte';
	//	import Form from '$lib/form/form/Profile.svelte';

	let { brood=$bindable(), pair, standard, i } = $props();

	const validate = {
		start:      v => validator(v).date().orNull().isValid(),
		eggs:       v => validator(v).number().range( 1, 99999 ).orNull().isValid(),
		fertile:    v => validator(v).number().range( 0, brood.eggs ).orNullIf( brood.eggs === null ).isValid(),
		hatched:    v => validator(v).number().range( 0, brood.fertile ).orNullIf( brood.fertile === null ).isValid(),
	}

	$effect( () => {
		brood.grade = aab.brood.layer( brood.eggs, brood.hatched );
	} );

</script>


<div class='w-full flex flex-row gap-x-2 items-center' in:fade>
	<TextInput class='w-8' label={i===0?' #':null} value={i+1} disabled />
	<DateInput class='w-24' label={i===0?'Eigeleg am':null} bind:value={brood.start} validator={validate.start} />
	<NumberInput class='w-14' label={i===0?'Eier':null} bind:value={brood.eggs} validator={validate.eggs} />
	<NumberInput class='w-14' label={i===0?'Befruchtet':null} bind:value={brood.fertile} validator={validate.fertile} />
	<NumberInput class='w-14' label={i===0?'Geschlüpft':null} bind:value={brood.hatched} validator={validate.hatched} />
	Layer
</div>