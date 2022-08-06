<script>

    export let value;
    export let label;
    export let disabled = false;

    let classname = '';
    export { classname as class }

    value = value.toString();

    let on = {
        focus: () => {},
        blur: () => {},
    }


</script>

<div class='input cursor-pointer {classname} flex flex-col gap-0'>
    {#if label}
        <label class='label' for='input'>{label}</label>
    {/if}
    <input class='data cursor-pointer' id='input' type='button' {name} bind:value={value}
           {disabled} readonly
            on:focus={on.focus}
            on:blur={on.blur}
           on:click
    >
</div>

<style>
    input {
        @apply border rounded border-gray-400 bg-emerald-200;
    }
</style>