<script>
	import {fade, slide} from 'svelte/transition';
	import aab from '$lib/js/aab.js';
	import {daysBetween, dec, pct, txt } from '$lib/js/tools.js';
	import { DateInput, Label, NumberInput, TextInput, validator } from '../../form/Form.svelte';
	import Status from '$lib/cmp/form/Status.svelte';

	let { pair=$bindable(), standard } = $props();

	let days = $state( null );
	let result = $state( null );

	const validate = {
		start:      v => validator(v).date().orNullIf( pair.lay.end === null ).orNullIf( pair.lay.eggs !== null ).isValid(),
		end:        v => validator(v).date().after( pair.lay.start ).orNullIf( pair.lay.start === null || pair.lay.eggs === null ).isValid(),
		dames:      v => validator(v).number().range( 1,   99 ).orNullIf( pair.lay.end === null ).isValid(),
		eggs:       v => validator(v).number().range( 0, 9999 ).if( pair.lay.start !== null && pair.lay.end !== null && pair.lay.dames !== null && pair.lay.average === null ).orNullIf( pair.lay.end === null ).orNullIf( pair.lay.average !== null ).isValid(),
		average:    v => validator(v).number().range( 0, 399 ).if( pair.lay.eggs === null ).orNull().isValid(),
		weight:     v => validator(v).number().range( 0,  399 ).orNull().isValid(),
	}

	$effect( () => {
		const breed = standard.breeds[ pair.breedId ];
		days = daysBetween( pair.lay.start, pair.lay.end );
		pair.lay.result = pair.lay.average ? pair.lay.average : aab.layProduction( pair.lay.start, pair.lay.end, pair.lay.dames, pair.lay.eggs );
		pair.layGrade = breed ? aab.lay( pair.lay.result, breed.layEggs ) : null;
	})

</script>


<fieldset class='border p-2' in:fade>
	<legend>Legeleistung <Status /></legend>
	{#if pair.colorId && pair.sectionId !== 5 }
		<div class='flex flex-row gap-x-2' transition:slide>
			<div class='w-0'></div>
			<DateInput class='' label='Gesammelt ab' bind:value={pair.lay.start} validator={validate.start} />
			<DateInput class='' label='bis' bind:value={pair.lay.end} validator={validate.end} />
			<NumberInput class='w-16' label='Hennen' bind:value={pair.lay.dames} validator={validate.dames} />
			<NumberInput class='w-16' label='# Eier' title='Gezählt in dieser Zeitraum' bind:value={pair.lay.eggs} validator={validate.eggs} />
			<Label label={true} title='Eierzahl in Sammelzeit oder Durchschnittslegeleistung'> oder  </Label>
			<NumberInput class='w-16' label='⌀ Eier/Jahr' title='Durchschnitt Jahresleistung' bind:value={pair.lay.average} validator={validate.average} />
			<Label label={true}> : </Label>
			<NumberInput class='w-16' label={`⌀ Gewicht`} bind:value={ pair.lay.weight } validator={validate.weight}/>
			<div class='grow'></div>
			<Label label={true}>→</Label>

			<NumberInput class='w-16' label='Tagen' value={ days } disabled/>
			<NumberInput class='w-20' label={`Legen ( ${pair.breed.layEggs} )`} value={ pair.lay.result } disabled/>
			<Label label={true}> = </Label>
			<NumberInput class='w-14 font-bold' label='Note' value={dec( pair.layGrade, 1 )} disabled/>
		</div>
	{/if}
</fieldset>