<script>
    import { meta } from 'tinro';
    import { user } from '../../js/store.js';
    import BreedRow from './BreedRow.svelte';

    export let rootSection = null;

    let route = meta();

    function onOpen() {
        rootSection.open = ! rootSection.open;
    }

</script>

<div class='flex flex-col pl-4'>
    <div class='flex flex-row gap-x-1 my-2 border-b border-gray-300'>
        <div class='w-4'>⤷</div>
        <div class='grow cursor-pointer' on:click={onOpen}>
            {rootSection.name} ({rootSection.children.length + rootSection.breeds.length })
        </div>

        {#if rootSection.children.length === 0 && $user && $user.admin && open }

        {/if}
        <div class='grow'></div>
        <div class='w-12 text-xs'>[{rootSection.id}]</div>
        {#if $user && $user.admin && rootSection.children == 0 }
            <div class='w-4'> <a href={route.match+'/sparte/'+rootSection.id+'/rasse/0'}> [+] </a> </div>
        {/if}
    </div>

    {#if rootSection.open }
        {#each rootSection.children as childSection }
            <svelte:self rootSection={childSection}/>
        {/each}
        {#each rootSection.breeds as breed }
            <BreedRow {breed}/>
        {/each}
    {/if}
</div>