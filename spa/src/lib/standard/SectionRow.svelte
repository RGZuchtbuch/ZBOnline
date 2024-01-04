<script>
    import { createEventDispatcher } from 'svelte';
    import { slide } from 'svelte/transition';
    import { meta } from 'tinro';
    import { user } from '../../js/store.js';
    import BreedRow from './BreedRow.svelte';

    export let section = null;
    export let open = false;

    let openedChild = null;

    const dispatch = createEventDispatcher();
    const route = meta();

    function onOpen() {
        //section.open = ! section.open;
        dispatch( 'open', section );
    }

    function onOpenChild( event ) {
        console.log( 'E', event );
        const child = event.detail;
        if( openedChild === child ) { // close on opened or open on closed
            openedChild = null;
        } else {
            openedChild = event.detail;
        }
    }

</script>

<div class='flex flex-col pl-4' transition:slide>
    <div class='flex flex-row gap-x-1 my-2 border-b border-gray-300'>
        <div class='w-4'>⤷</div>
        <button type='button' class='grow font-bold text-left cursor-pointer' on:click={onOpen}>
            {section.name} ({section.children.length + section.breeds.length })
        </button>
        <div class='grow'></div>
        <div class='w-12 text-xs'>[{section.id}]</div>
        {#if $user && $user.admin && section.children.length === 0 }
            <a class='w-6' href={route.match+'/sparte/'+section.id+'/rasse/0'}>✚</a>
        {/if}
    </div>

    {#if open }
        <div class='flex flex-col' transition:slide>
            {#each section.children as childSection }
                <svelte:self section={childSection} on:open={onOpenChild} open={childSection === openedChild}/>
            {/each}
            {#each section.breeds as breed }
                <BreedRow {breed}/>
            {/each}
        </div>
    {/if}
</div>