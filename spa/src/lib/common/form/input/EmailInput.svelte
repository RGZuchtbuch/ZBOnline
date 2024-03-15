<script>
	import {getContext, onMount} from 'svelte';
    import dic from '../../../../js/dictionairy.js';
	import TextInput from './TextInput.svelte';

	export let element = null;

	let className = '';
	export { className as class };
	export let name = null;
	export let title = null;
	export let label = null;
	export let value = null;
	export let placeholder = null;
	export let error = dic.error.error; // message on invalid
	export let validator = null;

	export let disabled = false;

	const state = getContext( 'form'); // store


	onMount( () => {} );

</script>


<TextInput class={className} bind:value={value}
   bind:element={element} {name} {label} {placeholder} {title} {error} {validator} {disabled}
   on:input on:change on:focus on:blur
/>

