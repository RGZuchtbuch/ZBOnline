<script>
    import ColorRow from './ColorRow.svelte';
    export let breed = null;

    let open = false;

    function onOpen() {
        open = ! open;
    }

</script>

<div class='flex flex-col pl-8'>
    <div class='flex flex-row border-b border-gray-300 px-4 gap-x-1'>
        <div class='w-12'>{breed.id}</div>

        <div class='grow cursor-pointer' on:click={onOpen}>
            {breed.name} ({breed.colors.length})
        </div>
    </div>
    {#if open}
        {#each breed.colors as color }
            <ColorRow color={color}/>
        {/each}
    {/if}
</div>
