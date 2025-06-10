<script>
	import { fade, slide } from 'svelte/transition';
	import { dec, txt } from '$lib/js/toolbox.js';
	import { CheckBox, NumberInput, TextInput, Select, Status, validator } from '$lib/cmp/form/Form.svelte';

	let { pair=$bindable() } = $props();

	const thisYear = new Date().getFullYear();

	const validate = {
		year:       v => validator(v).number().range( 1950, thisYear+1).isValid(),
		name:       v => validator(v).string().orNullIf( pair.delete ).isValid(),
	}
</script>


<fieldset class='flex flex-col p-2 gap-x-4 border border-base rounded bg-white sticky top-0' in:slide>
	<legend>Stamm</legend>
	<div class='text-right'><Status /></div>
	<div class='flex flex-row gap-x-4 '>
		<TextInput class='w-56' label='Züchter' value={txt( pair.breeder.firstname ) + ' ' + txt( pair.breeder.infix ) + ' ' + txt( pair.breeder.lastname ) } disabled  />
		<NumberInput class='w-16' label='Jahr*' bind:value={pair.year} error={'Fehler'} validator={validate.year} />
		<TextInput class='w-24' label='Name*' bind:value={pair.name} error='Fehler' validator={validate.name} />
		<Select label='Gruppe*' bind:value={pair.group}>
			{#each [ 'I', 'II', 'III' ] as option}
				<option value={option}>{option}</option>
			{/each}
		</Select>

		{#if pair.sectionId}
			<div class='grow flex flex-row gap-x-1 justify-end' in:fade>
				<NumberInput class='w-20' label='Abstammung' value={ dec( pair.parentsGrade, 1 ) } disabled/>
				{#if pair.sectionId !== 5}
					<NumberInput class='w-20' label='Legeleistung' value={ dec( pair.layGrade, 1 ) } disabled/>
				{/if}
				<NumberInput class='w-20' label='Brutleistung' value={ dec( pair.broodGrade, 1 ) } disabled />
				<NumberInput class='w-20' label='Schauleistung' value={ dec( pair.showGrade, 1 ) } disabled />
			</div>
		{/if}
		<CheckBox label='Löschen' title='Nur wenn Name leer ist !' bind:value={pair.delete} disabled={ pair.name }/>
	</div>

</fieldset>

