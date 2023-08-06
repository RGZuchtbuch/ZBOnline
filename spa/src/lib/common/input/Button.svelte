<script>

    export let value;
    export let label = null;
    export let disabled = false;
    export let alert = false
    export let element;

    let classname = '';
    export { classname as class }

    value = value.toString();

    let on = {
        focus: () => {},
        blur: () => {},
    }


</script>

<div class='input {classname}' class:alert>
    {#if label !== null}
        <label class='label' for='input'>{label}</label>
    {/if}
    <input class='cursor-pointer font-bold' id='input' type='button' {name} bind:value={value} bind:this={element}
           {disabled} readonly
            on:focus={on.focus}
            on:blur={on.blur}
           on:click
    >
</div>

<style>
    .input {
        @apply cursor-pointer border rounded border-gray-400 bg-button flex flex-col gap-0
    }

    .alert {
        @apply bg-alert;
    }
</style>