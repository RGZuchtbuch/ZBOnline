<script>

    export let value;
    export let label;
    export let error = '';
    export let name = null;
    export let disabled = false;
    export let readonly = false;
    export let maxlength = 655535;
    export let minlength = 0;
    export let required = false;
    export let pattern = null;
    export let spellcheck = true;
    export let invalid = false;

    export let element;

    let classname = '';
    export { classname as class }

    //value = value ? value.toString() : null;

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
    <textarea class='data w-full h-32 p-4' class:invalid id='input' {name} bind:value bind:this={element}
           {required} {disabled} {readonly} {pattern}
            on:focus={on.focus}
            on:blur={on.blur}
           {spellcheck}
    ></textarea>

    <span class:invalid>
        {#if invalid} {error} {:else} &nbsp; {/if}
    </span>
</div>

<style>


</style>