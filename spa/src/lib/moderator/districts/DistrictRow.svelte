<script>
//    import {Router} from 'tinro'; // Router store
    import { createEventDispatcher } from 'svelte';
    import {meta} from "tinro";
    import api from '../../../js/api.js';

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
        edit = ! edit;
        if( edit ) {
            api.district.breeders.get(district.id).then(response => {
                breeders = response.breeders;
            })
        }
    }

    function onAddChild() {
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
    }

    $: onDistrict( district );
</script>

<div class='flex flex-col pl-4'>
    {#if district.moderated || district.children.length > 0 }
        <div class='flex border-b'>
            <div class='w-6'>&#10551;</div>

            {#if district.moderated}
                <a class='text-black cursor-pointer' href={route.match+'/'+district.id} title='Zum Verband'>{district.name}</a>
            {:else}
                <div class='text-gray-400 cursor-not-allowed' title='Kein zugang' >{district.name}</div>
            {/if}

        </div>
    {/if}

    {#if true || open}
        {#each district.children as child}
            <svelte:self district={child} parentModerated={moderated}/>
        {/each}
    {/if}

</div>



<style>
    select {
        background: green;
    }
</style>