<script>
    import { onMount } from "svelte";

    export let value = null;
    export let label = null;
    export let name = null;
    export let disabled = false;
    export let readonly = false;
    export let min = -1000000; //Number.MIN_VALUE;
    export let max = +1000000; //Number.MAX_VALUE;
    export let step = 1;
    export let error = min+'..'+max;
    export let required = false;
    export let invalid = false;
    export let element;

    let classname = '';
    export { classname as class }

    function validate() {
        if( value ) {
            invalid = value < min || value > max;
        } else {
            invalid = required;
        }
    }

    function onInput( event ) {
        validate();
    }

    onMount( () => validate() )

    $: validate( min, max, required );
</script>

<div class='input {classname} flex flex-col gap-0'>
    {#if label}
        <label class='label text-left' for='input'>{label}</label>
    {/if}
    <input class='data' class:invalid id='input' type='number' {name}
           bind:value={value} bind:this={element}
           {min} {max} {step}
           {disabled} {readonly} {required}
           on:input={onInput}
    >
    <span class:invalid>
        {#if invalid} {error} {:else} &nbsp; {/if}
    </span>
</div>

<style>
    input {
        @apply text-right;
    }

    .invalid {
        @apply border-red-600;
    }

    input:read-only {
        @apply border-gray-200 bg-none;
    }

</style>