<script>
    import {getContext} from 'svelte';

    let className = '';
    export { className as class };
    export let value = '⚑';
	export let title = 'Form status';

    const state = getContext( 'state'); // store from form

	let changed = false;
	let invalid = true;

    function onChange( dummy ) {
		changed = $state.changed;
		invalid = ! ( $state.valid === undefined || $state.valid );
    }

    $: onChange( $state ); // update on change of state
    // ☑ ⚑ ⚿ ⛔ ⏳ 😊 😲😕😪😈⚫
</script>

<div class='status {className}' class:changed class:invalid {title}> {value} </div>

<style>
	.status {
		color: #0A0;
        display: flex;
        align-items: center;
		padding: 0 1em;
		cursor: help;
    }
    .changed {
        color: orange;
    }
    .invalid {
        color: red;
    }


</style>