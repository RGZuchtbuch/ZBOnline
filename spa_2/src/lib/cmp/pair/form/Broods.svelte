<script>
	import {fade, slide} from 'svelte/transition';
	import {calculateLay, dec, txt } from '$lib/js/toolbox.js';
	import Form, { DateInput, NumberInput, RingInput, Select, TextInput, validator } from '../../form/Form.svelte';
	//	import Form from '$lib/form/form/Profile.svelte';
	import BroodLayer from './Brood.Layer.svelte';
	import BroodPigeon from './Brood.Pigeon.svelte';

	let { pair } = $props();
</script>


<fieldset class='flex flex-row gap-x-2 border p-2' in:fade>
	<legend>Brutleistung</legend>
	{#each pair.broods as brood}
		{#if pair.sectionId === 5 }
			<BroodPigeon {brood}/>
		{:else}
			<BroodLayer {brood}/>
		{/if}
	{/each}
</fieldset>