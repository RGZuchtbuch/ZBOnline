<script>
    import { getContext,  onDestroy, onMount } from 'svelte';

    export let element = null;

    let className = '';
    export { className as class };
    export let name = null;
    export let title = null;
    export let label = null;
    export let value = null;
    export let step = 1;
    export let min = null; // allow for numbers >= min
    export let max = null; // allow for numbers <= max
    export let error = 'Invalid'; // message on invalid
    export let validator = null;

    let disabled = false;
    let valid = true;

    const state = getContext( 'state'); // store

    function validate() { // called from form with this context
        if( validator ) valid = validator( value ); // only if dirty or was invalid, only the active, dirty input can become invalid by design!
        return valid;
    }

    function onInput( event ) {
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
    <input class='input number right' {name} class:valid bind:this={element} type='number' bind:value={value}
           {step} {min} {max} {title} {disabled}
           on:input on:change on:focus on:blur
    />
    <label class='error' class:valid for='number'>{error}</label>
</div>

<style>
    div {
        display:flex;
        flex-direction: column;
    }
    right {
        text-align: right;
    }
    .error.valid {
        visibility: hidden;
    }
</style>