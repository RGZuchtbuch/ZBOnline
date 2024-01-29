<script>
    import { createEventDispatcher } from 'svelte';
    import { slide } from 'svelte/transition';
    import { meta } from 'tinro';
    import { user } from '../../js/store.js';
    import BreedRow from './BreedRow.svelte';
    import NumberInput from '../common/form/input/NumberInput.svelte';
    import FormStatus from '../common/form/Status.svelte';
    import TextInput from '../common/form/input/TextInput.svelte';
    import Form from '../common/form/Form.svelte';
    import {dec} from '../../js/util.js';

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
        open = true;
    }
    function onEdit() {
        edit = ! edit;
    }

    function onOpen() {
        //section.open = ! section.open;
        dispatch( 'open', section );
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

</script>

<div class='flex flex-col pl-4' transition:slide>
    <div class='flex flex-row gap-x-1 my-2 border-b border-gray-300'>
        <div class='w-4'>⤷</div>
        <button type='button' class='grow font-bold text-left cursor-pointer' on:click={onOpen}>
            {section.name} ({section.children.length + section.breeds.length })
        </button>
        <div class='grow px-2 flex gap-x-2 justify-end font-bold italic text-sm'>
            {#if open && section.breeds.length > 0}
                <div class='w-28 text-center'>Eier u.Gewicht</div>
                |
                <div class='w-28 text-center'>Wiegen</div>
                |
                <div class='w-28 text-center'>Ring</div>
            {/if}
        </div>
        <small class='w-12 text-right'>[{section.id}]</small>

        {#if $user && $user.admin && section.children.length === 0 }
            <button type='button' on:click={onAddBreed} title='Rasse hinzufügen'> (✚) </button>
        {/if}

    </div>

    {#if open }
        <div class='flex flex-col' transition:slide>
            {#each section.children as childSection }
                <svelte:self section={childSection} on:open={onOpenChild} open={childSection === openedChild}/>
            {/each}
            {#each section.breeds as breed }
                <BreedRow {breed} on:open={onOpenBreed} open={ breed === openedBreed}/>
            {/each}
        </div>
    {/if}
</div>