<script>
    import { onMount } from 'svelte';

    export let value = 0;
    export let label;
    export let min = 0;
    export let max = 99;
    export let focus = false;
    export let disabled = true;

    let classname = '';
    export { classname as class }

    let inputElement;
    let number = value; // internal year
    let error;

    const on = {
        focus: () => focus = true,
        blur: () => focus = false,
    }

    function check( number ) {
        if( number >= min && number <= max ) {
            value = number;
            error = false;
        } else {
            error = 'Out of range '+min+'..'+max;
        }
    }

    function hasFocus() {
        return document.activeElement=inputElement;
    }



    $: check(number);

    onMount( () => {
        if( focus ) inputElement.focus();
    })
</script>

<div class={'main '+classname}>
    <div class='label' title={min+'-'+max}>{label}</div>
    <input id='value' type='number' bind:value={number} class:error bind:this={inputElement} on:focus={on.focus} on:blur={on.blur} {disabled}>
    {#if error && ! focus }<span class='error'>{error}</span>{/if}
</div>



<style>
    div.main {
        @apply inline-flex flex-col border-b-gray-700;
    }

    div.label {
        font-size: 0.7rem;
        line-height: 0.7rem;
        @apply text-gray-500;
    }

    input {
        @apply w-full;
        border: none;
        border-bottom: 1px solid rgb(0 255 0);
        padding: 0.2rem;
        text-align: right;
    }

    span.error {
        font-size: 0.7rem;
        line-height: 0.7rem;
        font-weight: 400;
        color: #ff3e00;
    }





    .error {
        border-bottom: 1px solid rgb(255 0 0);
        color: #ff3e00;
    }
</style>
