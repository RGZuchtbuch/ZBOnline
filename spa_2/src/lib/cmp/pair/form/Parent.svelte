<script>
	import {fade, slide} from 'svelte/transition';
	import {validator, NumberInput, RingInput, Select, TextInput } from '../../form/Form.svelte';
	import Form from '$lib/cmp/form/Form.svelte';
	import Choice from '../../form/input/Choice.svelte';

	let { parent = $bindable(), i } = $props();


	const validate = {
		ring: (v) => validator(v).ring().orNull().isValid(),
	}

</script>

<div class='w-full flex flex-row gap-x-2'>
	<TextInput class='w-8' label={i===0?'#':null} value={i+1} disabled />
	<TextInput class='w-12' label={i===0?'♂.♀':null} value={parent.sex} disabled />
	<RingInput label={i===0?'Ring':null} bind:value={parent.ring} validator={validate.ring}/>
	<NumberInput class='w-16' label={i===0?'Bewertung':null} bind:value={parent.score}/>
	<div class='grow'></div>
	<Select class='w-32' label={i===0?'Aus Stamm':null} bind:value={parent.pairId}>

	</Select>
	<div class='w-8'></div>
	<NumberInput class='w-16' label={i===0?'Legeleistung':null} value={95} disabled/>
	<NumberInput class='w-16' label={i===0?'Brutleistung':null} value={95} disabled/>
	<NumberInput class='w-16' label={i===0?'Schauleistung':null} value={95} disabled/>
</div>
