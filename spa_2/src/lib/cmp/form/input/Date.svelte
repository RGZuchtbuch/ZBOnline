<script>
	import {getContext, onMount} from 'svelte';
	import {toDate, toDateISO, toDateString} from '../validator.js';
	import TextInput from './Text.svelte';

    let { class:classname='', disabled=false, element=$bindable(), error='Fehler', label=null, name=null, placeholder=null, title=null, validator=null, value=$bindable() } = $props();

	let date = toDate( value );

    let localValue = $state( toDateString( date ) ); // German format
    let tempValue = value; // for detecting external change

    function update( v ) {

    }

	function onInput( event ) { // translate to iso date (2024-12-31)
		const date = toDate( localValue );
    	value = tempValue = date ? toDateISO( date ) : localValue; // valid date or faulty as was
	}

	function onBlur( event ) { // format valid date when done
		const date = toDate( value );
        localValue = date ? date.getDate().toString().padStart( 2, '0' )+'.'+(date.getMonth()+1).toString().padStart( 2, '0' )+'.'+date.getFullYear().toString().padStart(4, '0') : null; // to formatted locale
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


<TextInput class='w-28 {classname}'
    bind:element={element} bind:value={localValue}
    {label} {placeholder} {title} {error} {validator} {disabled}
/>

<style>
    right {
        text-align: right;
    }
</style>



