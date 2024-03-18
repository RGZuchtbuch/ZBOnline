<script>
    import {getContext, onMount} from 'svelte';
    import dic from '../../../../js/dictionairy.js';
	import {toRing, toRingString} from '../../../../js/util.js';
	import TextInput from './TextInput.svelte';

	let inputElement = null;

	let className = '';
	export { className as class };
    export let element = null;
    export let name = null;
	export let title = 'Ring D19 AB 678';
	export let label = null;
	export let value = null;
	export let error = dic.error.error; // message on invalid
	export let validator = null; // validation by Textinput, forwarded

	export let disabled = false;

    let localeValue = value; // D

    function onInput( event ) {
        const ring  = toRing( localeValue );
        value = ring ? toRingString( ring ) : localeValue; // valid date or faulty as was
    }

	function onBlur( event ) {
        localeValue = value; // value already updated by onInput
	}

    onMount( () => {
        element.addEventListener( 'input', onInput );
        element.addEventListener( 'blur', onBlur );
    })
</script>


<TextInput class={className} {name} bind:value={localeValue} {label} {title} {error} {validator} {disabled}
           bind:element={element} on:input on:change on:focus on:blur />

