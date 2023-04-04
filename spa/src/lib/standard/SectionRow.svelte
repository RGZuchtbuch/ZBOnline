<script>
    import BreedRow from './BreedRow.svelte';
    export let section = null;
    export let open = false;

    function onOpen() {
        console.log( 'Open', open);
        open = ! open;
    }

</script>

<div class='flex flex-col pl-8'>
    <div class='flex flex-row border-b border-gray-300 px-4 gap-x-1'>

        <div class='w-12'>{section.id}</div>

        <div class='grow cursor-pointer' on:click={onOpen}>
            {section.name} ({section.children.length + section.breeds.length })
        </div>
    </div>
    {#if open }
        {#each section.children as childSection }
            <svelte:self section={childSection}/>
        {/each}
        {#each section.breeds as breed }
            <BreedRow breed={breed}/>
        {/each}
    {/if}
</div>