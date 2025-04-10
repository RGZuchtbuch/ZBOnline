<script>
    import { getContext,  onDestroy, onMount } from 'svelte';

    let { autocomplete=null, class:classname='', disabled=false, element=$bindable(), error='!!!', label=null, maxlength=null, name=null, oninput=null, placeholder=null, title=null, type='text', validator=null, value=$bindable() } = $props();

    const form = getContext( 'form'); //form state

    let valid = $state( true );



    function validate() { // called from form with this context
        if( validator ) valid = validator( value ); // only if dirty or was invalid, only the active, dirty input can become invalid by design!
        return valid;
    }

    function onInput( event ) {
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

<div class='wrapper {classname}'>
    {#if label}
        <label class='label' for='number'> {label} </label>
    {/if}
    <input {type}
           class:valid
           bind:this={element} bind:value={value}
           {placeholder} {title} {maxlength} {disabled} {autocomplete}
           {oninput}
    />
    <label class='error' class:valid for='number'>{error}</label>
</div>

<style>

    input {
        text-align: left;
        padding: 0 0.5em;
    }

</style>