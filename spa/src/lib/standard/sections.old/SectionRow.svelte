<script>
    import { user } from '../../../js/store.js';
    import BreedRow from './BreedRow.svelte';

    export let section = null;
    export let open = false;

    function onOpen() {
        open = ! open;
    }

    function onAddBreed() {
        if( section ) {
            let newBreed = { id:null, name:'Neu', sectionId:section.id, broodGroup:null, lay:null, layWeight:null, sireRing:null, dameRing:null, sireWeight:null, dameWeight:null, info:null, colors: [] }
            section.breeds.splice(0, 0, newBreed);
            section = section;
        }
    }
</script>

<div class='flex flex-col pl-8'>
    <div class='flex flex-row border-b border-gray-300 px-4 gap-x-1'>

        <div class='w-12 text-xs'># {section.id}</div>

        <div class='grow cursor-pointer' on:click={onOpen}>
            {section.name} ({section.children.length + section.breeds.length })
        </div>

        {#if section.children.length === 0 && $user && $user.admin && open }
            <div class='cursor-pointer' title='Neue Rasse' on:click={onAddBreed}>[ + ]</div>
        {/if}
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