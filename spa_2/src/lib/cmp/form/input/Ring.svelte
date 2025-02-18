<script>

    import {getContext, onMount} from 'svelte';
	import {toRing, toRingString} from '../validator.js';
	import TextInput from './Text.svelte';

    let { class:classname='', disabled=false, element=$bindable(), error='!!!', label=null, name=null, oninput=null, placeholder=null, title=null, validator=null, value=$bindable() } = $props();

    let localValue = $state( value ); // D
    let tempValue = value; // for detecting extern change from parent on page reload oid

//    function update( v ) {
//        if( value !== tempValue ) { // changed extern
//            tempValue = localValue = value;
//        }
//    }

    function onInput( event ) { // from intern
        const ring  = toRing( localValue );
        value = tempValue = ring ? toRingString( ring ) : localValue; // valid date or faulty as was
    }

	function onBlur( event ) { // format valid date when done
        localValue = value; // value already updated by onInput
	}

    onMount( () => {
        element.addEventListener( 'input', onInput );
        element.addEventListener( 'blur', onBlur );
    })

    $effect( () => {
        if( value !== tempValue ) { // changed extern
            localValue = tempValue = value;
        }
    });
</script>


<TextInput class='w-32 {classname}'
    bind:value={localValue} bind:element={element}
    {disabled} {error} {label} {placeholder} {title} {validator}
    {oninput}
/>

