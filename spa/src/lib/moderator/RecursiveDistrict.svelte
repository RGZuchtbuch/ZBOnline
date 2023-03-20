<script>
//    import {Router} from 'tinro'; // Router store
    import { createEventDispatcher } from 'svelte';
    import api from '../../js/api.js';
    import {txt} from '../../js/util.js';
    import Button from '../input/Button.svelte';
    import Number from '../input/Number.svelte';
    import Select from '../input/Select.svelte';
    import Text from '../input/Text.svelte';

    export let district = null;
    let details = null;
    let breeders = null;


    const moderated = district ? district.moderated : false;
//    const editable = district.moderated;
//    const selectable = district.selectable;

    const dispatch = createEventDispatcher();

    function onOpen() {
        district.open = ! district.open;
    }

    function onEdit() {
        district.edit = ! district.edit;
        if( district.edit ) {
            // load district details
            if( district.id ) { // existing
                api.district.get(district.id).then(response => {
                    details = response.district;
                });
                // load candidate moderaters
 //               api.district.breeders.get(district.id).then(response => {
 //                   breeders = response.breeders;
 //               })
            } else { // new, not saved
                details = {
                    id:null, parentId:district.parentId, name: null, fullname: null, short: null, latitude: null, longitude: null,
                };
                breeders = [];

            }
        }
    }

    function onAddChild() {
        console.log( 'Add Child' );
        district.children.splice( 0, 0, {
            id:null, parentId:district.id, name:'Neu', children: [],
            editable:true, // to make it show
            moderatable:false,
            visible:true,

        });
        district.open = true; // show children including this one
        district = district; // redraw
    }

    function onChange( event ) {
        district.changed = true;
        district = district;
    }

    function onClick( event ) {
        console.log( district, event )
        if( district.moderated ) dispatch( 'select', district.id );
    }

    function onSelect( event ) {
        dispatch( 'select', event.detail );
    }

    function onSubmit() {
        console.log( 'Submit district', district );
        api.district.post( details ).then( response => {
           details.id = response.id;
           district.id = response.id;
           district.name = details.name;
           district.edit = false;
//           district = district; // redraw
        });
        district.changed = false;
    }
</script>

<div class='pl-6'>
    {#if district.moderated || district.childModerated }
        <div class='flex'>
            <div class='text-gray-400' class:moderated on:click={onClick} title="'Wähle Verband">&#10551; {district.name}</div>
            {#if district.childModerated }
                {#if district.open}
                    <span class='open' on:click={onOpen}>[ - ]</span>
                {:else}
                    <span class='open' on:click={onOpen}>[ + ]</span>
                {/if}
            {/if}
            {#if district.moderated}
                {#if district.edit}
                    <span class='button text-red-600' on:click={onEdit} title='schließen'>[ &#9998; ]</span>
                {:else}
                    <span class='button text-green-600' on:click={onEdit} title='bearbeiten'>[ &#9998; ]</span>
                {/if}
            {/if}
        </div>

        {#if district.edit }
            <form class='flex flex-col border border-gray-400 rounded m-4 p-2' on:input={onChange}>
                {#if details }
                    <Text class='w-64' bind:value={details.name} label='Name' required disabled/>

                    <Text class='w-128' bind:value={details.fullname} label='Name voll' required disabled/>
                    <Text class='w-24' bind:value={details.short} label='Name abk.' required disabled/>
                    <div class='flex gap-x-2'>
                        <Number class='w-32' bind:value={details.latitude} label='Breitegrad N]' min={MINLATITUDE} max={MAXLATITUDE} required disabled/>
                        <Number class='w-32' bind:value={details.longitude}  label='Längegrad O' min={MINLONGITUDE} max={MAXLONGITUDE} required disabled/>
                    </div>
                    <Text class='w-96' value={details.moderator.lastname+', '+details.moderator.firstname+' '+txt(details.moderator.infix)} label='Obmann' disabled />

                    {#if district.changed}
                        <Button class='edit' on:click={onSubmit} label='' value='speichern' />
                    {/if}
                {/if}
            </form>
        {/if}
        <div></div>

        {#if district.open}
            {#each district.children as child}
                <svelte:self district={child} on:select={onSelect}/>
            {/each}
        {/if}
    {/if}
</div>

<style>
    .button {
        @apply cursor-pointer;
    }
    .open {
        @apply cursor-pointer;
    }
    .edit {
        @apply cursor-pointer;
    }
    select {
        background: green;
    }

    .moderated {
        @apply cursor-pointer text-black;
    }
</style>