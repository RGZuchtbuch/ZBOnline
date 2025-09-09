<script>
	import { getContext,  onDestroy, onMount } from 'svelte';

	let { autocomplete=null, class:classname='', disabled=false, element=$bindable(), error='Fehler', label=null, name=null, placeholder=null, title=null, validator=null, value=$bindable() } = $props();

	// export let element = null;
	//
	// let className = '';
	// export { className as class };
	//
	// export let title = null;
	// export let name='group';
	// export let label = null;
	// export let value; // required
	// export let options = [false, true];
	// export let disabled = false;

	let valid = true;
	//    let dirty = false; // unvalidated, for changed element vs all others for validation

	const state = getContext( 'form'); // store
</script>

<div class={'border'+classname}>
	{#if label}
		<label class='label' for='radio'> {label} </label>
	{/if}
	<section class='flex flex-row gap-x-4'>
	{#each options as option }
		<div class='flex flex-col '>
			<input id='radio' class='input' type='radio' bind:group={value} name={name} value={option}>
			<label class='option'>{option}</label>
		</div>
	{/each}
	</section>
</div>

<style>
	.input {
        @apply accent-teal-500 px-0;
	}
    div {
        display:flex;
        flex-direction: column;
    }
    .option {
        line-height: 1.25em;
        font-size: 0.6em;
    }
    .error.valid {
        visibility: hidden;
    }
</style>