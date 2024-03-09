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

    let disabled = false;
    let valid = true;

    const state = getContext( 'state'); // store

    function validate() { // called from form with this context
        if( validator ) valid = validator( value ); // only if dirty or was invalid, only the active, dirty input can become invalid by design!
        return valid;
    }

    //function onInput( event ) {}

    onMount( () => { // catch input and register validator
    //    element.addEventListener( 'input', onInput );
        if( validator ) $state.validators.push( validate ); // add this.validate to the form's context
    });
    onDestroy( () => { // remove validator
        let index = $state.validators.indexOf( validate );
        if( index >= 0 ) $state.validators.splice( index, 1 ); // remove this validator;
    });

    function onInput( event ) {
        console.log( "E", event.target.checked );
    }

</script>

<div class='{className}'>
    {#if label}
        <label class='name' for='toggle'>{label}</label>
    {/if}
    <input id='toggle' type='checkbox' bind:checked={value} {title} {disabled}/>
    <label class='error' class:valid for='toggle'>{error}</label>
</div>

<style>
    div {
        display:flex;
        flex-direction: column;
    }
    input {
        position: relative;
        height: 1.5rem;
        width: 3rem;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        border-radius: 9999px;
        background-color: lightcoral;
        transition: all .3s ease;
    }

    input:checked {
        background-color: lightgreen;
    }

    input::before {
        position: absolute;
        content: "";
        left: calc(1.5rem - 1.6rem);
        top: calc(1.5rem - 1.6rem);
        display: block;
        height: 1.6rem;
        width: 1.6rem;
        cursor: pointer;
        border: 1px solid red;
        border-radius: 9999px;
        background-color: rgba(255, 255, 255, 1);
        box-shadow: 0 3px 10px rgba(100, 116, 139, 0.327);
        transition: all .3s ease;
    }

    input:hover::before {
        box-shadow: 0 0 0px 8px rgba( 255, 0, 0, 0.25);
    }

    input:checked:hover::before {
        box-shadow: 0 0 0px 8px rgba( 0, 255, 0, 0.25);
    }

    input:checked:before {
        transform: translateX(100%);
        border-color: green;
    }
    .error.valid {
        visibility: hidden;
    }
</style>