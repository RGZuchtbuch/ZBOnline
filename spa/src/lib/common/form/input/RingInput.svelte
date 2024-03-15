<script>
    import dic from '../../../../js/dictionairy.js';
	import {toRing} from '../../../../js/util.js';
	import TextInput from './TextInput.svelte';

	let inputElement = null;

	let className = '';
	export { className as class };
	export let name = null;
	export let title = 'Ring D19 AB 678';
	export let label = null;
	export let value = null;
	export let error = dic.error.error; // message on invalid
	export let validator = null; // validation by Textinput, forwarded

	export let disabled = false;

	function onBlur( event ) {
		const tempValue = toRing( value );
		if( tempValue ) value = tempValue.country+' '+ (tempValue.year%100) +' '+tempValue.code+' '+tempValue.number;
	}
</script>


<TextInput class={className} {name} bind:value={value} {label} {title} {error} {validator} {disabled} on:blur={onBlur} />

