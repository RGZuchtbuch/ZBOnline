<script>

    export let label = '';
    export let value = 0;
    export let min = 2000;
    export let max = new Date().getFullYear();
    export let step = 1;

    let classname = '';
    export { classname as class }

    const on = {
        blur: () => { console.log( 'Range', label, ' blur') },
        focus: () => { console.log( 'Range', label, ' focus') },
    }

</script>

<div class='input {classname} flex flex-col gap-0 pb-1'>
    {#if label}
        <label class='label text-left' for='input'>{label} : {value}</label>
    {/if}
    <input class='data mb-1' type='range' id='input'
        bind:value={value}
        {min} {max} {step}
        on:blur={on.blur}
        on:focus={on.focus}
    >
</div>

<style>
    input {
        @apply h-2 bg-gray-200 rounded appearance-none cursor-pointer dark:bg-gray-700 accent-green-700;
    }
</style>