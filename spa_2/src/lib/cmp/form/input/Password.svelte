<script>
    import { getContext,  onDestroy, onMount } from 'svelte';
    import '../form.css';
    import TextInput from '$lib/cmp/form/input/Text.svelte';

    let { class:classname='', disabled=false, element=$bindable(), error='!', label=null, name=null, placeholder=null, title=null, validator=null, value=$bindable() } = $props();

    let valid = true;

    const form = getContext( 'form'); // store

    function validate() { // called from form with this context
        if( validator ) valid = validator( value ); // only if dirty or was invalid, only the active, dirty input can become invalid by design!
        return valid;
    }
    function onInput( event ) {
        console.log( 'PWD on input' );
        if( value === '' ) value = null;
    }

    onMount( () => { // catch input and register validator
        element.addEventListener( 'input', onInput );
        if( validator ) form.validators.push( validate ); // add this.validate with it's context
    });

    onDestroy( () => { // remove validator
        let index = form.validators.indexOf( validate );
        if( index >= 0 ) form.validators.splice( index, 1 ); // remove this validator;
    });

</script>

<TextInput class={classname} type='password'
           bind:element={element} bind:value={value}
           {disabled} {error} {label} {name} {placeholder} {title} {validator}
           {oninput} {onchange} {onfocus} {onblur}
           autocomplete='current-password'
/>

<style>
    div {
        display:flex;
        flex-direction: column;
    }
    input.left {
        text-align: left;
        padding: 0 0.5em;
    }
    .error.valid {
        visibility: hidden;
    }
</style>