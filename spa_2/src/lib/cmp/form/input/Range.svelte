<script>
    import { getContext,  onDestroy, onMount } from 'svelte';

    let { class:classname='', disabled=false, element=$bindable(), error='Fehler', label=null, max=null, min=null, name=null, step=1, title=null, validator=null, value=$bindable() } = $props();

    const form = getContext( 'form'); // store
    let valid = $state( true );


    function validate() { // called from form with this context
        if( validator ) valid = validator( value ); // only if dirty or was invalid, only the active, dirty input can become invalid by design!
        return valid;
    }

    onMount( () => { // catch input and register validator
        if( validator ) form.validators.push( validate ); // add this.validate with it's context
    });
    onDestroy( () => { // remove validator
        let index = form.validators.indexOf( validate );
        if( index >= 0 ) form.validators.splice( index, 1 ); // remove this validator;
    });

</script>

<div class='{classname}'>
    {#if label}
        <label class='label' for='number'>{label}</label>
    {/if}

    <input type='range'
       class='input' class:valid
       bind:this={element} bind:value={value}
       {step} {min} {max} {title} {disabled}
    />

    <label class='error' class:valid for='number'>{error}</label>
</div>

<style>
    div {
        display:flex;
        flex-direction: column;
    }
    input {
        text-align: right;
        padding: 0;
    }
    label.error.valid {
        visibility: hidden;
    }
</style>