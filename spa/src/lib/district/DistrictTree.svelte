<script>
//    import {Router} from 'tinro'; // Router store
    import { createEventDispatcher } from 'svelte';
    import {meta} from "tinro";
    import api from '../../js/api.js';
    import {user} from '../../js/store.js';
    import {txt} from '../../js/util.js';
    import Button from '../input/Button.svelte';
    import Number from '../input/Number.svelte';
    import Select from '../input/Select.svelte';
    import Text from '../input/Text.svelte';

    export let district = null;
    export let open = false;

    const moderated = district ?? district.moderated;
    let show = false;
    let edit = false;
    let details = null;
    let changed = false;
    let breeders = null;

    const route    = meta();
    const dispatch = createEventDispatcher();


    function onToggleOpen() {
        open = ! open;
    }

    function onToggleShow() {
        show = ! show;
        if( show ) {
            // load district details
            if( district.id ) { // existing
                api.district.get(district.id).then(response => {
                    details = response.district;
                });
            } else { // new, default values
                details = {
                    id:null, parentId:district.parentId, name:null, fullname:null, short:null, latitude:null, longitude:null, moderatorId:null
                };
//                breeders = [];

            }
        }
    }

    function onToggleEdit() {
        console.log( 'edit' );
        api.district.breeders.get( district.id ).then( response => {
            breeders = response.breeders;
        })
        edit = ! edit;
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
        changed = true;
//        district = district;
    }

    function onSubmit() {
        console.log( 'Submit district', district );
        api.district.post( details ).then( response => {
           details.id = response.id;
           district.id = response.id;
           district.name = details.name;
           edit = false;
        });
        changed = false;
    }

    console.log( $user );
</script>

<div class='flex flex-col pl-6'>

        <div class='flex'>
            {#if moderated}
                <a class='border' href={route.match+'/'+district.id} title='Zum Verband'> &#10551; {district.name}</a>
            {:else}
                <div class='border text-gray-400' title='Kein zugang' > &#10551; {district.name}</div>
            {/if}

            {#if district.children.length > 0 }
                {#if open}
                    <div class='px-4 cursor-zoom-out text-red-600' on:click={onToggleOpen}>[{district.children.length}]</div>
                {:else}
                    <div class='px-4 cursor-zoom-in text-green-600' on:click={onToggleOpen}>[{district.children.length}]</div>
                {/if}
            {/if}
            <div class='grow'></div>
            {#if moderated}
                {#if show}
                    <div class='cursor-pointer border border-gray-400 rounded text-red-600 px-2' on:click={onToggleShow} title='schließen'>&#8505;</div>
                {:else}
                    <div class='cursor-pointer border border-gray-400 rounded text-green-600 px-2' on:click={onToggleShow} title='bearbeiten'>&#8505;</div>
                {/if}
            {/if}
        </div>

        {#if show && details }
            <form class='flex flex-col border border-gray-400 rounded m-4 p-2' on:input={onChange}>
                {#if details }
                    <div class='flex'>
                        <Text class='w-64' bind:value={details.name} label='Name' required disabled={!edit}/>
                        <div class='grow'></div>
                        {#if $user.admin && !changed}
                            <div class='border border-gray-400 rounded w-6 h-6 text-center text-green-600' on:click={onToggleEdit}>&#9998;</div>
                        {/if}
                    </div>
                    <Text class='w-128' bind:value={details.fullname} label='Name voll' required disabled={!edit}/>
                    <Text class='w-24' bind:value={details.short} label='Name abk.' required disabled={!edit}/>
                    <div class='flex gap-x-2'>
                        <Number class='w-32' bind:value={details.latitude} label='Breitegrad N]' min={MINLATITUDE} max={MAXLATITUDE} required disabled={!edit}/>
                        <Number class='w-32' bind:value={details.longitude}  label='Längegrad O' min={MINLONGITUDE} max={MAXLONGITUDE} required disabled={!edit}/>
                    </div>
                    {#if edit && breeders }
                        <Select bind:value={details.moderatorId}>
                            <option value={null}></option>
                            {#each breeders as breeder}
                                <option value={breeder.id}> {txt(breeder.lastname)}, {txt(breeder.firstname)} {txt(breeder.infix)}</option>
                            {/each}
                        </Select>
                    {:else}
                        <Text class='w-96' value={details.moderator ? details.moderator.lastname+', '+details.moderator.firstname+' '+txt(details.moderator.infix) : null } label='Obmann' disabled />
                    {/if}
                    {#if edit && changed}
                        <Button class='edit' on:click={onSubmit} label='' value='speichern' />
                    {/if}
                {/if}
            </form>
        {/if}
        <div></div>

        {#if open}
            {#each district.children as child}
                <svelte:self district={child}/>
            {/each}
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