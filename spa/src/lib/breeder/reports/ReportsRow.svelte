<script>
//    import {Router} from 'tinro'; // Router store
    import { createEventDispatcher } from 'svelte';
    import {meta} from "tinro";
    import api from '../../../js/api.js';
    import {user} from '../../../js/store.js';
    import {txt} from '../../../js/util.js';
    import Button from '../../common/input/Button.svelte';
    import Number from '../../common/input/Number.svelte';
    import Select from '../../common/input/Select.svelte';
    import Text from '../../common/input/Text.svelte';
import DistrictDetails from "./ReportsDetails.svelte";

    export let district = null;
    export let open = false;
    export let parentModerated = false;;
    //export let role = 'breeder'; // breeder, moderator or admin, dont want role here!

    let moderated = false;
    let show = false;
    let edit = false;
    let details = null;
    let changed = false;
    let children = null;
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
        edit = ! edit;
        if( edit ) {
            api.district.breeders.get(district.id).then(response => {
                breeders = response.breeders;
            })
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

    function onDistrict( district ) {
        moderated = district.moderated;
//        moderated = $user && ( parentModerated || $user.id === district.moderatorId ) // took out admin for testing
    }

    $: onDistrict( district );
</script>

<div class='flex flex-col pl-6'>

    {#if district.moderated}
        <div class='flex border-b'>
            <div class='w-4'>&#10551;</div>
            {#if false && district.children.length > 0 }
                {#if open}
                    <div class='w-6 cursor-zoom-out text-red-800' on:click={onToggleOpen} title='Schließen'>[-]</div>
                {:else}
                    <div class='w-6 cursor-zoom-in text-green-800' on:click={onToggleOpen} title='Öffnen'>[+]</div>
                {/if}
            {:else}
                <div class='w-6'></div>
            {/if}

            {#if district.moderated}
                <a class='text-black cursor-pointer' href={route.match+'/'+district.id} title='Zum Verband'>{district.name}</a>
            {:else}
                <div class='text-gray-400 cursor-not-allowed' title='Kein zugang' >{district.name}</div>
            {/if}
            <div class='w-16'></div>

            <div class='grow'></div>
            {#if district.moderated}
                {#if show}
                    <div class='cursor-pointer text-red-600 px-2' on:click={onToggleShow} title='schließen'>&#8505;</div>
                {:else}
                    <div class='cursor-pointer text-green-600 px-2' on:click={onToggleShow} title='bearbeiten'>&#8505;</div>
                {/if}
            {/if}
        </div>

        <DistrictDetails district={district.id} />

    {/if}


</div>

{#if true || open}
    {#each district.children as child}
        <svelte:self district={child} parentModerated={moderated}/>
    {/each}
{/if}

<style>
    .edit {
        @apply cursor-pointer;
    }
    select {
        background: green;
    }
</style>