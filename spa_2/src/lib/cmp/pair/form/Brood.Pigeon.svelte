<script>
	import {fade, slide} from 'svelte/transition';
	import aab from '$lib/js/aab.js';
	import { dec, pct, txt } from '$lib/js/tools.js';
	import Form, { DateInput, Label, NumberInput, RingInput, Select, TextInput, validator } from '../../form/Form.svelte';
	//	import Form from '$lib/form/form/Profile.svelte';

	let { brood=$bindable(), pair, standard, i } = $props();
	brood.eggs = 2; // default 2 eggs per brood for pigeons

	const validate = {
		start:      v => validator(v).date().orNull().isValid(),
		//eggs:       v => validator(v).number().range( 1, 99999 ).orNull().isValid(),
		//fertile:    v => validator(v).number().range( 0, pair.brood.eggs ).orNullIf( pair.brood.eggs === null ).isValid(),
		hatched:    v => validator(v).number().range( 0, 2 ).orNull().isValid(),
		ringed:     v => validator(v).date().after( brood.start ).orNull().isValid(),
	}

	$effect( () => {
		// I do not calculate as theres only 1 brood, so no valid grade
		brood.grade = brood.hatched != null ?
			aab.brood.pigeon(pair.breed.broodGroup, 2, 2 * brood.hatched) : // 2* as 1 brood does not give grade
			null; //dcks pair.brood.eggs ? 100 * pair.brood.hatched / pair.brood.eggs : null; // TODO
	} );

</script>


<div class='w-full flex flex-row gap-x-2' in:fade>
	<TextInput class='w-8' label={i===0?'#':null} value={i+1} disabled />
	<DateInput class='w-24' label={i===0?'Gelegt am':null} bind:value={brood.start} validator={validate.start} />
	<!--NumberInput class='w-14' label='Geschlüft' value={brood.eggs} disabled /-->
	<NumberInput class='w-14' label={i===0?'Geschlüpft':null} bind:value={brood.hatched} validator={validate.hatched} />
	<span class='w-4' ></span>
	<DateInput class='w-24' label={i===0?'Beringt am':null} bind:value={brood.ringed} validator={validate.ringed} />
	<span class='w-2' ></span>
	<RingInput class='' label={i===0?'Küken #1':null} bind:value={brood.chicks[0]} validator={validate.ring} />
	<RingInput class='' label={i===0?'Küken #1':null} bind:value={brood.chicks[1]} validator={validate.ring} />

	{#if brood.hatched != null }
		<div class='grow flex flex-row gap-x-1 justify-end' in:fade>
			<Label label={i===0}> → </Label>
			<TextInput class='w-16' label={i===0 ? 'Brutleistung' : null} title='Schlüpfrate' value={ pct( brood.hatched, 2 ) } align='right' disabled />
			<Label label={i===0}> = </Label>
			<NumberInput class='w-16 font-bold' label={i===0?'Note':null} title='Schlüpfnote' value={ dec( brood.grade, 1 ) } disabled/>
		</div>
	{/if}
</div>