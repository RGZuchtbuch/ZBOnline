<script>
	import {fade, slide} from 'svelte/transition';
	import Form, { DateInput, Status, TextArea, validator } from '../../form/Form.svelte';
	import {onMount} from 'svelte';
	//	import Form from '$lib/form/form/Profile.svelte';

	let { pair=$bindable() } = $props();

	const validate = {
		//score:       v => validator(v).number().range( 0, 999 ).orNull().isValid(),
	}
</script>


<fieldset class='flex flex-col border p-2' in:fade>
	<legend>Notizen <Status /></legend>
	<TextArea class='h-32' bind:value={pair.notes} />
</fieldset>