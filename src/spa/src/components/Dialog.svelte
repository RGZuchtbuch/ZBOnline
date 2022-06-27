<script>
    import { createEventDispatcher } from 'svelte';
    const dispatch = createEventDispatcher();

    const on = {
        close: () => {
            dispatch( 'close', null );
        }
    }
</script>

<div class='main absolute self-center w-full h-full flex flex-col'>
    <div class='flex flex-col justify-center mx-auto my-4 border rounded bg-gray-100 '>
        <div class=''>Dialog</div>

        <div class='inline-block flex flex-row justify-end cursor-pointer'> <span on:click={on.close}>Ok</span></div>
    </div>
</div>

<style>
    div.main {
        background-color: rgba( 0.5, 0.5, 0.5, 0.25 );
    }
</style>