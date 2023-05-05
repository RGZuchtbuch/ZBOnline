<script>
    import { createEventDispatcher } from 'svelte';

    const dispatch = createEventDispatcher();

    export let title = 'Rassegeflügel Zuchtbuch';

    function onHelp() {
        dispatch( 'help', false );
    }


    //
</script>



<div class='modal'>
    <div class='w-256 flex bg-header border rounded-t pb-2'>
        <div class='w-8'></div>
        <h2 class='grow text-center text-white'>{title}</h2>
        <div class='w-8 justify-center m-2 circled bg-alert' on:click={onHelp}>X</div>
    </div>
    <div class='w-256 h-full overflow-y-scroll border border-t-gray-400 rounded-b scrollbar p-8'>
        <slot></slot>
    </div>
</div>


<style>
    .modal {
        z-index: 2;
        @apply absolute backdrop-blur-xl w-256 top-0 bottom-0 flex flex-col py-6 justify-center cursor-pointer;
    }

    ol {
        @apply list-inside list-disc;
    }

</style>