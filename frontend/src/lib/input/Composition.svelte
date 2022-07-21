<script>

    export let sires;
    export let dames;
    export let label;
    export let error;
    export let name;
    export let disabled = false;
    export let readonly = false;
    export let required = false;

    const pattern = /^(\d+).(\d+)$/;

    let classname = '';
    export { classname as class }

    let value = sires+'.'+dames;
    let invalid = false;
    let on = {
        focus: () => {},
        blur: () => {},
    }

    $: validate( value );

    function validate( value ) {
        const match = value.match( pattern );
        if( match ) {
            sires = match[1];
            dames = match[2];
            invalid = false;
        } else if( ! required && ( value==='' || value===null ) ) {
            invalid = false;
        } else {
            invalid = true;
        }
        console.log('V', value, invalid, match);
    }

</script>

<div class='input {classname} flex flex-col gap-0'>
    {#if label}
        <label class='label' for='input'>{label}</label>
    {/if}
    <input class='data' class:invalid id='input' type='text' {name} bind:value={value} {disabled} {readonly}
           on:focus={on.focus}
           on:blur={on.blur}
    >
    {#if invalid && ! disabled}
        <span class:invalid>{error}</span>
    {/if}
</div>

<style>
    input {
        @apply text-center;
    }
</style>