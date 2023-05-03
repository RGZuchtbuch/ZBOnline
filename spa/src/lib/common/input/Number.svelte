<script>

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

    let classname = '';
    export { classname as class }

</script>

<div class='input {classname} flex flex-col gap-0'>
    {#if label}
        <label class='label text-left' for='input'>{label}</label>
    {/if}
    <input class='data' class:invalid id='input' type='number' {name}
           bind:value={value}
           {min} {max} {step}
           {disabled} {readonly} {required}
    >
    {#if ! disabled && invalid }
        <span class:invalid>{error}</span>
    {/if}
</div>

<style>
    input {
        @apply text-right;
    }

    .invalid {
        @apply border-red-600;
    }

</style>