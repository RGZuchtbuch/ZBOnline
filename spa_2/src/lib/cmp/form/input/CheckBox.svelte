<script>
	import { getContext,  onDestroy, onMount } from 'svelte';

	let { class:classname='', disabled=false, element=$bindable(), error='!', label=null, name=null, onchange=null, placeholder=null, title=null, validator=null, value=$bindable() } = $props();
	const form = getContext( 'form'); // store
	let valid = $state( true );

	function validate() { // called from form with this context
		if( validator ) valid = validator( value ); // only if dirty or was invalid, only the active, dirty input can become invalid by design!
		return valid;
	}
	function onInput( event ) {
		if( value === '' ) value = null;
	}

	onMount( () => { // catch input and register validator
		element.addEventListener( 'input', onInput );
		if( form && form.validators && validator ) form.validators.push( validate ); // add this.validate with it's context
	});
	onDestroy( () => { // remove validator
		if( form && form.validators ) {
			let index = form.validators.indexOf(validate);
			if (index >= 0) form.validators.splice(index, 1); // remove this validator;
		}
	});

</script>

<div class='wrapper {classname} items-center'>
	{#if label}
		<label class='label' for='number'> {label} </label>
	{/if}
	<input type='checkbox'
	       class='input w-4 h-4 mt-2' class:valid
	       bind:this={element} bind:checked={value}
		   {disabled} {name} {placeholder} {title}
		   {onchange}
	/>
	<label class='error' class:valid for='number'>{error}</label>
</div>

<style>
	label.label {
		@apply pl-0;
	}
</style>