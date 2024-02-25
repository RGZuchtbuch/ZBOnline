<script>
    import { createEventDispatcher } from 'svelte';
    import { slide } from 'svelte/transition';
    import { meta } from 'tinro';
    import dic from '../../js/dictionairy.js';

    import { user } from '../../js/store.js';
    import BreedRow from './BreedRow.svelte';
    import NumberInput from '../common/form/input/NumberInput.svelte';
    import FormStatus from '../common/form/Status.svelte';
    import TextInput from '../common/form/input/TextInput.svelte';
    import Form from '../common/form/Form.svelte';
    import {dec} from '../../js/util.js';
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
    function onEdit() {
        edit = ! edit;
    }

    function onToggle() {
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

<div class='flex flex-col pl-4' transition:slide>
    <div class='flex flex-row gap-x-1 my-2 border-b border-gray-300'>

        <Toggle bind:open={open} enabled={section.children.length > 0 || section.breeds.length > 0 } class='text-orange-600'/>
        <div class='font-bold text-left' title={dic.title.section}>
            {section.name}
        </div>
        <div class='text-xs'>({section.children.length + section.breeds.length })</div>

        <div class='grow flex gap-x-2 justify-end text-sm font-bold italic'>
            {#if open && section.breeds.length > 0}
                <div class='flex border-x border-gray-400'>
                    <div class='w-14 text-center'>Eier/J</div>
                    <div class='w-8 text-center'>Wiegt</div>
                    <div class='w-4 text-center'></div>
                </div>
                <div class='flex'>
                    <div class='w-28 text-center'>Gewicht ♂.♀</div>
                    <div class='w-2'></div>
                </div>
                <div class='flex border-x border-gray-400'>
                    <div class='w-20 text-center'>Ring ♂.♀</div>
                    <div class='w-2'></div>
                </div>
            {/if}
        </div>


        {#if $user && $user.admin }
            <div class='flex w-8 text-xs text-red-600'>
                <div class='w-4'></div>
                {#if open && section.children.length === 0}
                    <button class='w-4' type='button' on:click={onAddBreed} title='Rasse hinzufügen'> [✚] </button>
                {/if}
            </div>
        {/if}

        <small class='w-10 text-gray-400 text-right cursor-auto'>[{section.id}]</small>
    </div>

    {#if open }
        <div class='flex flex-col' transition:slide>
            {#each section.children as childSection }
                <svelte:self section={childSection} on:open={onOpenChild} open={childSection === openedChild}/>
            {/each}
            {#each section.breeds as breed }
                <BreedRow {breed} on:open={onOpenBreed} open={ breed === openedBreed} on:removed={onBreedRemoved}/>
            {/each}
        </div>
    {/if}
</div>