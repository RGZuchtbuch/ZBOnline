<script>
    import {getContext} from 'svelte';

    // states: disabled, not changed, invalid, submittable
	//const states = { initial:null, waiting:'waiting', changed:'changed', invalid:'invalid', valid:'valid', disabled:'disabled', stored:'stored', error:'server error :(' };

	const defaultValue = { waiting:'...', changed:'...', invalid:'X', valid:'Ok', disabled:'x', stored:'Fertig' };
    let { class:classname='', disabled=false, element=$bindable(), error='!', label=null, name=null, placeholder=null, title=null, validator=null,
        values=$bindable( defaultValue ) } = $props();

//    export let value = { waiting:'waiting...', changed:'Changed', invalid:'Invalid', valid:'Valid', disabled:'Disabled' };

    const form = getContext( 'form'); // store from form

</script>

<input class='submit {classname} {form.state}'
       type='submit'
       value={ values[ form.state ] }
       disabled={form.state!=='valid'}
       title={form.state}
/>

<style>

	input {
		vertical-align: text-top;
        background-color: #d97706;
        font-weight: bold;
        text-align: center;
        color: white;
        border: solid 1px grey;
        border-radius: 0.25em;
        padding: 0 0.25em;
    }

	.waiting {
        color: gray;
        background: lightgray;
	}
	.changed {
		color: gray;
		background: lightgray;
    }
	.invalid {
		color:white;
		background:salmon;
    }

    input {
        color:white;
        background: #fb923c;
    }
	input:disabled {
        color: white;
        background: lightgray;
    }

</style>