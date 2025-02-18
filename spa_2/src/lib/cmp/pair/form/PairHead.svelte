<script>
	import {app} from '$lib/js/store.svelte.js';
	import { fade, slide } from 'svelte/transition';
	import { dec, txt } from '$lib/js/toolbox.js';
	import { Form, NumberInput, TextInput, Select, Status, validator } from '$lib/cmp/form/Form.svelte';
	import Breed from '$lib/cmp/pair/form/Breed.svelte';
//	import Number from '$lib/cmp/form/input/Number.svelte';
//	import Text from '$lib/cmp/form/input/Text.svelte';
//	import Select from '$lib/cmp/form/input/Select.svelte';
//	import Status from '$lib/cmp/form/Status.svelte';

	let { pair } = $props();

	//let breeder = store.breeder;
	//let pair    = store.pair;

	const thisYear = new Date().getFullYear();

	const validate = {
		year:       v => validator(v).number().range( 1950, thisYear+1).isValid(),
		name:       v => validator(v).string().notNull().isValid(),
	}

</script>


<fieldset class='flex flex-col p-2 gap-x-4 border border-base rounded' in:slide>
	<legend>Stamm</legend>
	<div class='flex flex-row gap-x-4 '>
		<TextInput class='w-32' label='Züchter' value={txt( pair.breeder.firstname ) + ' ' + txt( pair.breeder.infix ) + ' ' + txt( pair.breeder.lastname ) } disabled  />
		<NumberInput class='w-16' label='Jahr*' bind:value={pair.year} error={'Fehler'} validator={validate.year} />
		<TextInput class='w-24' label='Name*' bind:value={pair.name} error='Fehler' validator={validate.name} />
		<Select label='Gruppe*' bind:value={pair.group}>
			{#each [ 'I', 'II', 'III' ] as option}
				<option value={option}>{option}</option>
			{/each}
		</Select>
		<div class='grow' ></div>
		<NumberInput class='w-20' label='Abstammung' value={ dec( pair.parentsGrade, 1 ) } disabled/>
		<NumberInput class='w-20' label='Legeleistung' value={ dec( pair.layGrade, 1 ) } disabled/>
		<NumberInput class='w-20' label='Brutleistung' value={ dec( pair.broodGrade, 1 ) } disabled />
		<NumberInput class='w-20' label='Schauleistung' value={ dec( pair.showGrade, 1 ) } disabled />
		<Status />
	</div>

</fieldset>

