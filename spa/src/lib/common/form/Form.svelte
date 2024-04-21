<script>
    import { createEventDispatcher, setContext, onMount, onDestroy } from 'svelte';
    import { writable } from 'svelte/store';

    let className = '';
    export { className as class };

    export let autoSave = true;

    export let valid = true; // if valid, only save when changed and valid
    export let changed = false; // if form data changed
    export let disabled = false; // disable editing form

    //export let errorMessage = 'System error on post, warn administrator !';

    const dispatch = createEventDispatcher();

    const state = writable( { disabled:disabled, changed:false, valid:true, validators:[], error:false } ); // store in context
    //let error = false; // when post failes
    let formElement; // the formelement
    let saveTimeout = null; // timer
    let validateTimeout = null; // timer

    setContext( 'form', state );

//    let help = localStorage.getItem( 'help' );

    window.addEventListener( 'beforeunload', beforeUnload ); // when tab is left by user
//    localStorage.setItem('help', 'init' );

    function beforeUnload( event ) { // needs keepalive in post !
        if( autoSave && $state.changed ) { // show dialog
//            event.preventDefault(); // triggers dialog;
//            event.returnValue = true;
            trySubmit();
            console.log('Form submit on leave');
        }
    }


    function validate() { // all registered validators, triggered by timeout
        let tempValid = true;
        for (const validator of $state.validators) { // all registered validators
            tempValid = validator() && tempValid; // call first otherwise call will be skipped after first false
        }
        $state.valid = valid = tempValid;
    }


    function onDisabled( dummy ) {
        $state.disabled = disabled;
    }

    function onInput( event ) { // called after children got input and init validate and autosave
        if( ! $state.changed ) {
            $state.changed = changed = true;
            localStorage.setItem('help', 'changed' );
        }
        $state.valid = valid = undefined; // to be validated...
        clearTimeout( validateTimeout ); // reset timers to fire after timeout on last change
        clearTimeout( saveTimeout );
        validateTimeout = setTimeout( validate, 750 ); // validate n ms after last input
        if( autoSave ) saveTimeout = setTimeout( trySubmit, 1500 ); // try save n ms after last input
    }

    function onSubmit( event ) { // enter or submit button
        event.preventDefault();
        console.log( 'Form onSubmit' );
        if( $state.changed ) {
            validate();
            trySubmit();
        }
    }

    function trySubmit() { // called by submit or autosave timeout
        if( $state.changed && $state.valid ) {
            $state.changed = changed = false; // after post success
            dispatch( 'submit' ); // let outside do the actual submit
            return true; // submitted
        } else {
            return false; // no submit
        }
    }

    onMount( () => {
        validate(); // check on init errors
        formElement.addEventListener('input', onInput); // each keystroke in form
        formElement.addEventListener('submit', onSubmit); // catching enter
    });

    onDestroy( () => {
        // should save all unsaved...
        //alert('Saving all');
    })

    $: onDisabled( disabled );

</script>


<form bind:this={formElement} class:valid>
    <fieldset class='{className}' {disabled}>
        <slot />
    </fieldset>
</form>

<style>
    form {
        padding : 0;
        margin: 0;
    }

    fieldset {
        border: 0;
        margin: 0;
    }

    form :global(label.name) { /* for inputs label */
        font-size: 0.6em;
        color: black;
        text-align: left;
        padding-left: 0.5em;
    }

/*
    input, select, option {
        @apply h-6 py-0 px-1;
    }

 */
    form :global( input select ) {
        height: 1.5em;
    }
    form :global( textarea ) {
        height: 6em;
    }
    form :global(.input) {
        margin: 0.0em;
        color: black;
        border: solid 1px red;
        border-radius: 0.25em;
        padding: 0;
    }
    form :global(.input:disabled) {
        color: black;
        background-color: #EEE;
        border: solid 1px #DDD;
    }
    form :global(.valid) {
        border: solid 1px grey;
    }
    form :global(label.error) { /* for inputs error label */
        color: red;
        font-size: 0.6em;
        text-align: left;
        padding: 0.0em 0.5em;
    }


    form .error {
        visibility: visible;
    }

    .hidden {
        color: red;
        font-size: 0.8em;
        text-align: center;
        visibility: hidden;
    }

</style>