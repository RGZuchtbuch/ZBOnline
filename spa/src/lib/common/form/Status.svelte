<script>
    import {getContext} from 'svelte';
	import dic from '../../../js/dictionairy.js';

    let className = '';
    export { className as class };
    export let value = '⚑';

	let title = dic.title.saved;

    const state = getContext( 'form'); // store from form

	let changed = false;
	let invalid = true;

    function onChange( dummy ) {
		changed = $state.changed;
		invalid = ! ( $state.valid === undefined || $state.valid );
		title = invalid ? dic.title.error : changed ? dic.title.changed : dic.title.saved;
    }

    $: onChange( $state ); // update on change of state
    // ☑ ⚑ ⚿ ⛔ ⏳ 😊 😲😕😪😈⚫
</script>

<div class='status {className}' class:changed class:invalid {title}> {value} </div>

<style>
	.status {
		color: #0A0;
        vertical-align: text-top;
		padding: 0 0.25em;
		cursor: default;
    }
    .changed {
        color: orange;
	    cursor: wait;
    }
    .invalid {
        color: red;
	    cursor: no-drop;
    }


</style>