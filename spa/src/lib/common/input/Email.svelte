<script>

    export let value = null;
    export let label = null;
    export let name = null;
    export let disabled = false;
    export let readonly = false;
    export let maxlength = 128;
    export let minlength = 5;
    export let error = 'Unvollständiger eMail Adresse';
    export let required = false;
    export let invalid = false;

    let pattern = '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$';

    let classname = 'w-64';
    export { classname as class }

    //value = value ? value.toString() : null;

    function validate( value ) {
        if( value ) {
            invalid = value.match( pattern ) === null ;
        } else {
            invalid = required;
        }
        console.log('EMail invalid', value, invalid );
    }

    $: validate( value );

</script>

<div class='input {classname} flex flex-col gap-0'>
    {#if label}
        <label class='label' for='input'>{label}</label>
    {/if}
    <input class='data' class:invalid id='input' type='text' {name} bind:value={value}
           {minlength} {maxlength}
           {required} {disabled} {readonly}
    >
    {#if invalid && ! disabled}
        <span class:invalid>{error}</span>
    {/if}
</div>

<style>

</style>