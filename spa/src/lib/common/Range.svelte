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
        <label class='label text-left' for='input'>{label} : {value}</label>
    {/if}
    <input class='data mb-1' type='range' id='input'
        bind:value={value} bind:this={element}
        {min} {max} {step}
        on:blur={on.blur}
        on:focus={on.focus}
    >
</div>

<style>
    input {
        @apply h-2 bg-header rounded appearance-none cursor-pointer dark:bg-gray-700 accent-red-600;
    }
</style>