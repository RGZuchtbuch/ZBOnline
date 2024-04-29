<script>
	import {getContext, onMount} from 'svelte';
	import {toDate, toDateISO, toDateString} from '../../../../js/util.js';
    import dic from '../../../../js/dictionairy.js';
	import TextInput from './TextInput.svelte';

	export let element = null;
	let className = '';
	export { className as class };
	export let name = null;
	export let title = '31.12.2023';
	export let label = null;
	export let value = null;
	export let placeholder = null;
	export let error = dic.error.error; // message on invalid
	export let validator = null;

	export let disabled = false;

	const state = getContext( 'form'); // store
	let date = toDate( value );

    let localValue = toDateString( date ); // German
    let tempValue = value; // for detecting external change

    function update( v ) {
        if( value !== tempValue ) { // changed extern
            localValue = tempValue = value;
        }
    }

	function onInput( event ) {
		const date = toDate( localValue );
    	value = tempValue = date ? toDateISO( date ) : localValue; // valid date or faulty as was
	}

	function onBlur( event ) { // format valid date
		const date = toDate( value );
        localValue = date ? date.getDate().toString().padStart( 2, '0' )+'.'+(date.getMonth()+1).toString().padStart( 2, '0' )+'.'+date.getFullYear().toString().padStart(4, '0') : null; // to formatted locale
	}

	onMount( () => {
		element.addEventListener( 'input', onInput );
		element.addEventListener( 'blur', onBlur );
	})

    $: update( value ); // from extern or intern....

</script>


<TextInput class={className} bind:value={localValue} bind:element={element}
   {name} {label} {placeholder} {title} {error} {validator} {disabled}
   on:input on:change on:focus on:blur
/>

<style>
    right {
        text-align: right;
    }
</style>



