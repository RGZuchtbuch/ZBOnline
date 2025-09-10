<script module>// wa context='module' but that is depricated
    import Form from './Form.svelte';
    import CheckBox from './input/CheckBox.svelte';
    import DateInput from './input/Date.svelte';
    import EmailInput from './input/Email.svelte';
    import Label from './input/Label.svelte';
    import NumberInput from './input/Number.svelte';
    import PasswordInput from './input/Password.svelte';
    import RangeInput from './input/Range.svelte';
    import RingInput from './input/Ring.svelte';
    import Select from './input/Select.svelte';
    import Status from './Status.svelte';
    import Submit from'./Submit.svelte';
//    import Switch from './input/Switch.svelte.todo';
    import TextInput from './input/Text.svelte';
    import TextArea from './input/TextArea.svelte';
    import validator from './validator.js';

    export { Form, CheckBox, DateInput, EmailInput, Label, NumberInput, PasswordInput, RangeInput, RingInput, Select, Status, Submit, TextArea, TextInput, validator };

</script>

<script>
    import { setContext, onMount, onDestroy } from 'svelte';
    import './form.css';

    let { autosubmit=false, children, class:classname='', disabled=false, initialState='stored', legend=null, onsubmit=null, valid=$bindable(), validateafter=500, submitafter=1500 } = $props();

    const states = { initial:null, waiting:'waiting', changed:'changed', invalid:'invalid', valid:'valid', disabled:'disabled', stored:'stored', error:'server error :(' };
    const form = $state( { state:states.stored, validators:[] } ); // initial comes from server, so stored

    //let error = false; // when post failes

    let element; // the formelement

    let submitTimeout = null; // timer
    let validateTimeout = null; // timer

    setContext( 'form', form ); // use getContext in input components

    function onInput( event ) { // called after children got input and init validate and autosave
        form.state = states.changed;
        clearTimeout( validateTimeout ); // stop validate timer
        clearTimeout( submitTimeout ); // stop autosubmit timer
        validateTimeout = setTimeout( validate, validateafter ); // restart validate timer
        if( autosubmit ) {
            submitTimeout = setTimeout( submit, submitafter ); // restart autosubmit timer
        }
    }

    async function onSubmit( event ) { // enter or submit button
        event.preventDefault();
        console.log( 'Form manual submit' );
        await submit();
    }


    function validate() { // all registered validators, triggered by timeout
        let valid = true;
        for( const validator of form.validators ) { // all registered validators
            valid = validator() && valid; // call validator first otherwise call will be skipped when valid already false
        }
        form.state = valid ? states.valid : states.invalid;
    }

    async function submit() { // triggered by timeout or submit button
        if( form.state===states.valid ) {
            if( onsubmit ) {
                const success = await onsubmit(); // onsubmit from host !!
                if( success ) {
                    form.state = states.stored; // waiting
                }
            } // let outside do the actual submit
        }
    }

    onMount( () => {
        validate(); // initial validate, why, trust server data
        if( form.state === states.valid ) form.state = initialState
        element.addEventListener('input', onInput); // each keystroke in form
        element.addEventListener('submit', onSubmit); // catching enter
    });

    onDestroy( () => {
        if( form.state === states.changed) {
            validate();
            if( form.state === states.valid ) {
                // TODO still save if changed ?
            }
        }
    })

</script>

<form bind:this={element} class:valid onsubmit={ ()=>console.log('Click') }>
    <fieldset class='{classname}' {disabled}> <!-- to allow disabled for all -->
        {#if legend}<legend>{legend}</legend>{/if}
        {@render children()}
    </fieldset>
</form>

<style>
    form {
        padding : 0;
        margin: 0;
    }
    form > fieldset {
        border: 0;
    }
</style>