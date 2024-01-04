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
    export let title = null;
    export let element = null;

    let classname = '';
    export { classname as class }


    function validate( value ) {
        invalid = ( required && ( value===null || value.length === 0 ) );
    }

    $: validate( value );

</script>

<div class='input {classname} flex flex-col gap-0' {title}>
    {#if label}
        <label class='label text-left' for='input'>{label}</label>
    {/if}
    <input class='' class:invalid id='input' type='text' {name} bind:value={value} bind:this={element}
           {minlength} {maxlength}
           {required} {disabled} {readonly} {pattern}
           {spellcheck}
    >

    <span class:invalid>
        {#if invalid} {error} {:else} &nbsp; {/if}
    </span>
</div>

<style>
    input:read-only {
        @apply border-gray-200 bg-none;
    }
</style>