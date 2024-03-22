<script>
    import { getContext,  onDestroy, onMount } from 'svelte';
    import dic from '../../../../js/dictionairy.js';

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
    let valid = true;
//    let dirty = false; // unvalidated, for changed element vs all others for validation

    const state = getContext( 'form'); // store

    function validate() { // called from form with this context
        if( validator ) valid = validator( value ); // only if dirty or was invalid, only the active, dirty input can become invalid by design!
        return valid;
    }
    function onInput( event ) {
        if( value === '' ) value = null;
    }

    onMount( () => { // catch input and register validator
        element.addEventListener( 'input', onInput );
        if( validator ) $state.validators.push( validate ); // add this.validate with it's context
    });
    onDestroy( () => { // remove validator
        let index = $state.validators.indexOf( validate );
        if( index >= 0 ) $state.validators.splice( index, 1 ); // remove this validator;
    });

</script>

<div class='{className}'>
    {#if label}
        <label class='name' for='number'> {label} </label>
    {/if}
    <input class='input' {name} class:valid bind:this={element} type='password' bind:value={value}
           {placeholder} {title} {disabled}
           on:input on:change on:focus on:blur
    />
    <label class='error' class:valid for='number'>{error}</label>
</div>

<style>
    div {
        display:flex;
        flex-direction: column;
    }
    left {
        text-align: left;
    }
    .error.valid {
        visibility: hidden;
    }
</style>