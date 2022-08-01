<script>

    export let value;
    export let label;

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
           readonly
            on:focus={on.focus}
            on:blur={on.blur}
           on:click
    >
</div>

<style>

</style>