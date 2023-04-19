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
    export let validator = null;

    let classname = '';
    export { classname as class }


    let on = {
        focus: () => {},
        blur: () => {},
    }


    function validate( value, min, max ) { // using component value
        if( ! readonly ) {
            invalid = !((value >= min && value <= max) || (!required && value == null));
        }
    }

    $: validate( value, min, max, required );
</script>

<div class='input {classname} flex flex-col gap-0'>
    {#if label}
        <label class='label text-left' for='input'>{label}</label>
    {/if}
    <input class='data' class:invalid id='input' type='number' {name}
           bind:value={value}
           {min} {max} {step}
           {disabled} {readonly} {required}
           on:focus={on.focus}
           on:blur={on.blur}
           on:change on:input
    >
    {#if invalid && ! disabled}
        <span class:invalid>{error}</span>
    {/if}
</div>

<style>
    input {
        @apply text-right;
    }

</style>