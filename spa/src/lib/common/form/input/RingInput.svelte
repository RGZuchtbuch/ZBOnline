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

    let localValue = value; // D
    let tempValue = value; // for detecting extern change

    function update( v ) {
        if( value !== tempValue ) { // changed extern
            tempValue = localValue = value;
        }
    }

    function onInput( event ) { // from intern
        console.log( 'RI', localValue, event.target.value );
        const ring  = toRing( localValue );
        value = tempValue = ring ? toRingString( ring ) : localValue; // valid date or faulty as was
    }

	function onBlur( event ) {
        localValue = value; // value already updated by onInput
	}

    onMount( () => {
        element.addEventListener( 'input', onInput );
        element.addEventListener( 'blur', onBlur );
    })

    $: update( value );
</script>


<TextInput class={className} {name} {label} {title} {error} {validator} {disabled}
           bind:value={localValue}
           bind:element={element}
           on:input on:change on:focus on:blur />

