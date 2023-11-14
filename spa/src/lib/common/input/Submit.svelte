<script>

    export let value = null;
    export let label = null;
    export let disabled = false;
    export let element;
    export let title = 'Speichern';

    let classname = '';
    export { classname as class }

    let on = {
        focus: () => {},
        blur: () => {},
    }


</script>

<div class='input cursor-pointer {classname} flex flex-col gap-0' {title}>
    {#if label !== null}
        <label class='label' for='input'>{label}</label>
    {/if}
    <input class='data cursor-pointer' class:disabled id='input' type='submit' {name} bind:value={value} bind:this={element}
           {disabled} readonly
            on:focus={on.focus}
            on:blur={on.blur}
           on:click
    >
</div>

<style>
    input {
        @apply border rounded border-gray-400 bg-button;
    }
    .disabled {
        @apply bg-red-100;
    }
</style>