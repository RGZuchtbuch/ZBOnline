<script>

    export let value;
    export let label;
    export let name = null;
    export let disabled = false;
    export let readonly = false;
    export let min = -1000000; //Number.MIN_VALUE;
    export let max = +1000000; //Number.MAX_VALUE;
    export let step = 1;
    export let error = '!'
    export let required = false;

    let classname = '';
    export { classname as class }

    let invalid = false;
    validate( value, min, max );

    let on = {
        focus: () => {},
        blur: () => {},
    }

    $: validate( value, min, max );

    function validate( value, min, max ) { // using component value
        invalid = ! (( value >= min && value <= max ) || (! required && value == null )) ;
    }
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