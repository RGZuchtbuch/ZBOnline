<script>

    export let value;
    export let label;
    export let name;
    export let disabled = false;
    export let readonly = false;
    export let maxlength = 524288;
    export let minlength = 0;
    export let required = false;
    export let pattern = null;

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
    <input class='data' class:error id='input' type='text' {name} bind:value={value} {minlength} {maxlength} {required} {disabled} {readonly} {pattern}
        on:focus={on.focus}
        on:blur={on.blur}
    >
    {#if error && ! disabled}
        <span class:error>Tada</span>
    {/if}
</div>

<style>

</style>