<script>

    export let value;
    export let label;
    export let error = '!';
    export let name = null;
    export let disabled = false;
    export let readonly = false;
    export let maxlength = 255;
    export let minlength = 0;
    export let required = false;
    export let pattern = null;
    export let spellcheck = true;
    export let invalid = false;

    let classname = '';
    export { classname as class }


    function validate( value ) {
        invalid = ( required && value.length === 0 );
    }

    $: validate( value );

</script>

<div class='input {classname} flex flex-col gap-0'>
    {#if label}
        <label class='label' for='input'>{label}</label>
    {/if}
    <input class='data' class:invalid id='input' type='text' {name} bind:value={value}
           {minlength} {maxlength}
           {required} {disabled} {readonly} {pattern}
           {spellcheck}
    >

    {#if invalid && ! disabled}
        <span class:invalid>{error}</span>
    {/if}
</div>

<style>

</style>