<script>
    import {getContext} from 'svelte';


    let { class:classname='', element=$bindable(), title=null, validator=null, value=$bindable('⚑') } = $props();
    let form = getContext( 'form' );

	const titles = { waiting:'Warten auf Antwort...', changed:'Geändert', invalid:'Fehler', valid:'Gültig', disabled:'Disabled', stored:'Gespeichert' };


	// ☑ ⚑ ⚿ ⛔ ⏳ 😊 😲😕😪😈⚫
</script>

<span class='status {classname} {form.state}' title={titles[ form.state ]}> {value} </span>

<style>
	.status {
		color: #0A0;
		vertical-align: top; /* when not in flex */
		padding: 0 0.25em 0.75em 0.25em;
		cursor: default;
    }

    .waiting {
        color: blue;
    }
    .changed {
        color: lightgray;
    }
    .invalid {
        color: red;
    }
    .valid {
        color:orange;
    }
    .disabled {
	    visibility: hidden;
    }
    .stored {
	    color:limegreen;
    }


</style>