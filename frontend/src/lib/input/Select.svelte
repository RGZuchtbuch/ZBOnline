<script>

    export let value;
    export let options = [];
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

    <select class='data' class:error id='input'
            bind:value={value}
            {name}
            {disabled} {readonly}
            {required}
            on:focus={on.focus}
            on:blur={on.blur}
    >
        {#each options as { value, name }, i}
            <option value={value}>{name}</option>
        {/each}
    </select>

    {#if error && ! disabled}
        <span class:error>Tada</span>
    {/if}
</div>

<style>

</style>
