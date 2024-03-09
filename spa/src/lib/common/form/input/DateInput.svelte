<script>
	import {getContext, onMount} from 'svelte';
	import {toDate} from '../../../../js/util.js';
	import TextInput from './TextInput.svelte';

	export let element = null;
	let className = '';
	export { className as class };
	export let name = null;
	export let title = 'Ring D19 AB 678';
	export let label = null;
	export let value = null;
	export let placeholder = null;
	export let error = 'invalid format'; // message on invalid
	export let validator = null;

	export let disabled = false;

	const state = getContext( 'state'); // store
	let date = toDate( value );

	let localeValue = date ? date.getDate()+'.'+(date.getMonth()+1)+'.'+date.getFullYear() : null; // D

	let component = null;

	function onInput( event ) {
		const date = toDate( localeValue );
    	value = date ? date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate() : localeValue; // valid date or faulty input
	}

	function onBlur( event ) { // format valid date
		const date = toDate( value );
		localeValue = date ? date.getDate().toString().padStart( 2, '0' )+'.'+(date.getMonth()+1).toString().padStart( 2, '0' )+'.'+date.getFullYear().toString().padStart(4, '0') : null; // to formatted locale
	}

	onMount( () => {
		element.addEventListener( 'input', onInput );
		element.addEventListener( 'blur', onBlur );
	})

</script>


<TextInput class={className} bind:value={localeValue} bind:this={component}
   bind:element={element} {name} {label} {placeholder} {title} {error} {validator} {disabled}
   on:input on:change on:focus on:blur
/>

<style>
    right {
        text-align: right;
    }
</style>



