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
    export let error = dic.error.error; // message on invalid
    export let validator = null;

    export let disabled = false;
    let valid = true;
    let dirty = false; // unvalidated, for changed element vs all others for validation

    const state = getContext( 'state'); // store

//    $state.validators.push( validate );

    function validate() { // called from form with this context
        if( validator ) valid = validator( value ); // only if dirty or was invalid, only the active, dirty input can become invalid by design!
        return valid;
    }

    function onInput( event ) {
        //dirty = true; // needs validation
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
        <label class='name' for='number'>{label}</label>
    {/if}
    <select class='input bg-red-300' {name} class:valid bind:this={element} bind:value={value} on:input on:change on:focus on:blur >
        <slot />
    </select>
    <label class='error' class:valid for='number'>{error}</label>
</div>

<style>
    div {
        display:flex;
        flex-direction: column;
    }
    .error.valid {
        visibility: hidden;
    }
</style>