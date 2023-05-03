<script>

    export let value = null;
    export let label = null;
    export let error = '!';
    export let name = null;
    export let title = null;
    export let disabled = false;
    export let readonly = false;
    export let required = false;


    let classname = '';
    export { classname as class }

    let invalid = false;

    let on = {
        focus: () => {},
        blur: () => {},
    }

    $: validate( value )

    function validate() {
        invalid = required ? value===null : false;
    }

</script>

<div class='input {classname} flex flex-col gap-0' {title}>
    {#if label}
        <label class='label' for='input'>{label}</label>
    {/if}

    <select class:invalid class:disabled id='input'
            bind:value={value}
            {name}
            {disabled} {readonly} {required}
            on:focus={on.focus}
            on:blur={on.blur}
            on:change
    >
            <slot></slot>
    </select>

    {#if invalid && ! disabled}
        <span class:invalid>{error}</span>
    {/if}
</div>

<style>
</style>
