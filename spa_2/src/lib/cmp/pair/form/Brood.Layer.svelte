<script>
	import {fade, slide} from 'svelte/transition';
	import aab from '$lib/js/aab.js';
	import { dec, pct, txt } from '$lib/js/tools.js';
	import { DateInput, Label, NumberInput, RingInput, Select, TextInput, validator } from '../../form/Form.svelte';

	let { brood=$bindable(), pair=$bindable(), standard, i } = $props();

	const validate = {
		start:      v => validator(v).date().orNull().isValid(),
		eggs:       v => validator(v).number().range( 1, 99999 ).orNull().isValid(),
		fertile:    v => validator(v).number().range( 0, brood.eggs ).orNull().isValid(),
		hatched:    v => validator(v).number().range( 0, brood.fertile ? brood.fertile : brood.eggs ).orNullIf( brood.eggs === null ).isValid(),
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

	{#if brood.eggs>0 && brood.hatched>=0 }
		<div class='grow flex flex-row gap-x-1 justify-end' in:fade>
			<Label label={i===0}> → </Label>
			<TextInput class='w-16' label={i===0?'Brutleistung':null} title='Schlüpfrate' value={ pct( brood.hatched, brood.eggs ) } align='right' disabled/>
			<!--Label label={i===0}> = </Label-->
			<!--TextInput class='w-14 font-bold' label={i===0?'Note':null} title='Schlüpfnote' value={ brood.grade > 89 ? dec( brood.grade, 1 ) : 'o.B.' } disabled/-->
			<span class='w-16'></span>
		</div>
	{/if}

</div>