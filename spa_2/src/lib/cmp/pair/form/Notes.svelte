<script>
	import {fade, slide} from 'svelte/transition';
	import Form, { DateInput, TextArea, validator } from '../../form/Form.svelte';
	import {onMount} from 'svelte';
	//	import Form from '$lib/form/form/Profile.svelte';

	let { pair=$bindable() } = $props();

	const validate = {
		//score:       v => validator(v).number().range( 0, 999 ).orNull().isValid(),
	}
</script>


<fieldset class='flex flex-col border p-2' in:fade>
	<legend>Notizen</legend>
	<TextArea class='h-24' bind:value={pair.notes} />
</fieldset>