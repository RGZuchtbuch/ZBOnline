<script>

    export let value;
    export let label;
    export let error = '';
    export let name = null;
    export let disabled = false;
    export let readonly = false;
    export let maxlength = 524288;
    export let minlength = 0;
    export let required = false;
    export let pattern = null;
    export let spellcheck = true;

    let classname = '';
    export { classname as class }

    //value = value ? value.toString() : null;
    let invalid = false;

    let on = {
        focus: () => {},
        blur: () => {},
    }

    $: validate( value );

    function validate( value ) {
        let valid = value ? pattern ? value.match( pattern ) : true : ! required;
        invalid = ! valid;
    }

</script>

<div class='input {classname} flex flex-col gap-0'>
    {#if label}
        <label class='label' for='input'>{label}</label>
    {/if}
    <input class='data' class:invalid id='input' type='text' {name} bind:value={value}
           {minlength} {maxlength}
           {required} {disabled} {readonly} {pattern}
            on:focus={on.focus}
            on:blur={on.blur}
           {spellcheck}
    >
    {#if invalid && ! disabled}
        <span class:invalid>{error}</span>
    {/if}
</div>

<style>

</style>