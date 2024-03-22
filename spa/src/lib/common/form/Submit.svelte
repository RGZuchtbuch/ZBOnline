<script>
    import {getContext} from 'svelte';

    // states: disabled, not changed, invalid, submittable

    let className = '';
    export { className as class };
    export let submitValue = 'Submit';
    export let noChangeValue = submitValue;
    export let invalidValue = 'Invalid';
    export let errorValue = 'System error !'; // not expected

    export let enforce = false;

    let formDisabled = false; // when form disabled, do not show
	let value = submitValue;
//    let submittable = true; // ready when changed and valid
    let noChange = true;
    let invalid = false;
    let error = false;
    let disabled = false;

    const state = getContext( 'form'); // store from form

    if( enforce ) $state.changed = true;

    function onChange( dummy ) {
        console.log( 'Change', $state );
        formDisabled = $state.disabled;
//        submittable = $state.changed && $state.valid && ! $state.error;
		noChange = ! $state.changed;
        invalid  = ! $state.valid;
        error    = $state.error;
        value = noChange ? noChangeValue : invalid ? invalidValue : error ? errorValue : submitValue;
        disabled = noChange || invalid || error;
    }

    $: onChange( $state ); // update on change of state
</script>


<input class='submit {className}' class:formDisabled class:noChange class:invalid class:error type='submit' {value} {disabled} />

<style>
	.formDisabled {
		visibility: hidden;
	}

    input.submit {
        color: black;
        background-color: #fc5226;
        font-size: 1em;
	    font-weight: bold;
        text-align: center;
	    color: white;
        border: solid 1px grey;
        border-radius: 0.25em;
        padding: 0.5em;
    }

    input.submit.noChange {
        color: grey;
        background-color: lightgrey;
    }
    input.submit.invalid {
        color: black;
        background-color: lightgrey;
    }
    input.submit.error {
        color: grey;
        background-color: lightgrey;
    }

</style>