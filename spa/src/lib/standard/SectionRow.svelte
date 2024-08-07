<script>
    import { createEventDispatcher } from 'svelte';
    import { slide } from 'svelte/transition';
    import { meta } from 'tinro';
    import dic from '../../js/dictionairy.js';

    import { user } from '../../js/store.js';
    import BreedRow from './BreedRow.svelte';
    import Toggle from '../common/OpenClose.svelte';

    export let section = null;
    export let open = false;

    let openedBreed = null;
    let openedChild = null;
    let edit = false;


    const dispatch = createEventDispatcher();
    const route = meta();

    function  onAddBreed() {
        const breed = { id:0, sectionId:section.id, name:'Rasse...', layEggs:null, layWeight:null, sireWeight:null, dameWeight:null, sireRing:null, dameRing:null, broodGroup:0, info:null, aoc:false, colors:[] };
        section.breeds = [ breed, ...section.breeds ]; // add default breed
        openedBreed = breed; // make sure the new breed is open to alow for adding colors
        open = true;
    }
    function onToggle() {

        open = ! open;
    }

    function onOpenBreed( event ) {
        const breed = event.detail;
        if( openedBreed === breed ) { // close on opened or open on closed
            openedBreed = null;
        } else {
            openedBreed = event.detail;
        }
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

    function onBreedRemoved( event ) {
        const breed = event.detail;
        console.log( 'onBreedChange', section, breed );
        const index = section.breeds.indexOf( breed ); // find it
        if( index >= 0 ) { // found
            section.breeds.splice(index, 1); // remove it
            section = section;
        }
    }

</script>

<div class='flex flex-col pl-2 md:pl-4' transition:slide>
    <div class='flex flex-row gap-x-1 my-2 border-b border-gray-300'>

        <Toggle bind:open={open} enabled={section.children.length > 0 || section.breeds.length > 0 } class='text-orange-600'/>

        <button class='border-0 font-bold text-left' title={dic.title.section} on:click={onToggle}>
            {section.name}
        </button>

        {#if section.children.length > 0 }
            <div class='hidden md:block text-xs' title={dic.title.children}>({ section.children.length })</div>
        {/if}
        {#if section.breeds.length > 0 }
            <div class='hidden md:block text-xs' title={dic.title.breeds}>({ section.breeds.length })</div>
        {/if}

        <div class='hidden md:flex grow gap-x-2 justify-end text-sm font-bold italic'>
            {#if open && section.breeds.length > 0}
                {#if section.layers }
                    <div class='flex'>
                        <div class='w-14 text-center'>Eier/J</div>
                        <div class='w-14 text-center'>Wiegt</div>
                    </div>
                    <div class='flex'>
                        <div class='w-28 text-center'>Gewicht ♂.♀</div>
                    </div>
                    <div class='flex'>
                        <div class='w-20 text-center'>Ring ♂.♀</div>
                    </div>
                {:else}
                    <div class='flex'>
                        <div class='w-28 text-center'>Brutgruppe</div>
                    </div>
                    <div class='flex'>
                        <div class='w-28 text-center'>Gewicht ♂.♀</div>
                    </div>
                    <div class='flex'>
                        <div class='w-20 text-center'>Ring ♂.♀</div>
                    </div>
                {/if}
            {/if}
        </div>


        {#if $user && $user.admin }
            <div class='flex text-xs text-red-600'>
                <div class='w-4'></div>
                {#if open && section.children.length === 0}
                    <button class='w-4 border-0' type='button' on:click={onAddBreed} title='Rasse hinzufügen'>
                        <img src="assets/add.svg" alt="Rasse hinzufügen">
                    </button>
                {/if}
            </div>
        {/if}

        <small class='w-6 text-gray-400 text-3xs text-right cursor-auto' title={section.id}>[{section.id}]</small>
    </div>

    {#if open }
        <div class='flex flex-col' transition:slide>
            {#each section.children as childSection, i }
                <svelte:self section={childSection} on:open={onOpenChild} open={childSection === openedChild}/>
            {/each}
            {#each section.breeds as breed, i }
                <BreedRow {section} {breed} on:open={onOpenBreed} open={ breed === openedBreed} on:removed={onBreedRemoved}/>
            {/each}
        </div>
    {/if}
</div>