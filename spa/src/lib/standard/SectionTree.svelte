<script>
    import { meta } from 'tinro';
    import { user } from '../../js/store.js';
    import BreedRow from './BreedRow.svelte';

    export let rootSection = null;
    export let open = false;

    let route = meta();

    function onOpen() {
        console.log( 'Open', open);
        open = ! open;
    }

    function onAddBreed() {
        if( rootSection ) {
            console.log('Add');
            let newBreed = { id:null, name:'Neu', sectionId:rootSection.id, broodGroup:null, lay:null, layWeight:null, sireRing:null, dameRing:null, sireWeight:null, dameWeight:null, info:null, colors: [] }
            rootSection.breeds.splice(0, 0, newBreed);
            rootSection = rootSection;
        }
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

    {#if open }
        {#each rootSection.children as childSection }
            <svelte:self rootSection={childSection}/>
        {/each}
        {#each rootSection.breeds as breed }
            <BreedRow breed={breed}/>
        {/each}
    {/if}
</div>