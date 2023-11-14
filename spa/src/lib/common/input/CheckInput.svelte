<script>

    export let label = '';
    export let title = null;
    export let value = 0;
    export let min = 2000;
    export let max = new Date().getFullYear();
    export let step = 1;
    export let element;


    let classname = '';
    export { classname as class }

    const on = {
        blur: () => { console.log( 'Range', label, ' blur') },
        focus: () => { console.log( 'Range', label, ' focus') },
    }

</script>

<div class='input {classname} flex flex-col gap-0 pb-1' title={title}>
    {#if label}
        <label class='label text-left' for='input'>{label}</label>
    {/if}
    <input class='data mb-1' type='checkbox' id='input'
        bind:checked={value} bind:this={element}
        on:blur={on.blur}
        on:focus={on.focus}
    >
</div>

<style>
</style>