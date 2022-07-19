<script>

    export let value;
    export let label;
    export let name;
    export let disabled = false;
    export let readonly = false;
    export let required = false;

    let classname = '';
    export { classname as class }

    let error = true;
    let on = {
        focus: () => {},
        blur: () => {},
    }

</script>

<div class='input {classname} flex flex-col gap-0'>
    {#if label}
        <label class='label' for='input'>{label}</label>
    {/if}
    <input class='data' class:error id='input' type='date' {name}
           bind:value={value}
           {disabled} {readonly}
           {required}
           on:focus={on.focus}
           on:blur={on.blur}
    >
    {#if error && ! disabled}
        <span class:error>Tada</span>
    {/if}
</div>

<style>

</style>