<script>
    import { getContext,  onDestroy, onMount } from 'svelte';

    let { class:classname='', disabled=false, element=$bindable(), error='!!!', label=null, max=null, maxlength=null, min=null, name=null, oninput=null, placeholder=null, step=1, title=null, type='text', validator=null, value=$bindable() } = $props();

    let valid = $state( true );

    const form = getContext( 'form'); // store

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
        <label class='label' for='name'>{label}</label>
    {/if}
    <input type='number'
           class='input number right' class:valid
           bind:this={element} bind:value={value}
           {disabled} {min} {max} {name} {step} {title}
           {oninput}
    />
    <label class='error' class:valid for='number'>{error}</label>
</div>

<style>
    div {
        display:flex;
        flex-direction: column;
    }
    input {
        background-color: #FEE8;
    }
    input.valid {
        background-color: transparent;
    }
    input.right {
        text-align: right;
        padding: 0;
    }
    label.error.valid {
        visibility: hidden;
    }
</style>