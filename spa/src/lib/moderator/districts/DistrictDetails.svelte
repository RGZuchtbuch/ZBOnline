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

    {#if details }

        <form class='flex flex-col border border-gray-400 rounded m-4 p-2' on:input={onChange}>
            {#if details }
                <div class='flex'>
                    <Text class='w-64' bind:value={details.name} label='Name' required disabled={!edit}/>
                    <div class='grow'></div>
                    {#if $user.admin && !changed}
                        <div class='border-2 border-gray-400 rounded w-6 h-6 text-center text-green-600' on:click={onToggleEdit}>&#9998;</div>
                    {/if}
                </div>
                <Text class='w-128' bind:value={details.fullname} label='Name voll' required disabled={!edit}/>
                <Text class='w-24' bind:value={details.short} label='Name abk.' required disabled={!edit}/>
                <div class='flex gap-x-2'>
                    <Number class='w-32' bind:value={details.latitude} label='Breitegrad N]' min={MINLATITUDE} max={MAXLATITUDE} required disabled={!edit}/>
                    <Number class='w-32' bind:value={details.longitude}  label='Längegrad O' min={MINLONGITUDE} max={MAXLONGITUDE} required disabled={!edit}/>
                </div>
                    <Select bind:value={details.moderatorId} label='Obmann' disabled={! edit}>
                        {#if edit && breeders}
                            <option value={null}></option>
                            {#each breeders as breeder}
                                <option value={breeder.id} selected={breeder.id === details.moderator.id}>
                                    {txt(breeder.lastname)}, {txt(breeder.firstname)} {txt(breeder.infix)}
                                </option>
                            {/each}
                        {:else}
                            <option value={ details.moderator ? details.moderator.id : null } selected>
                                { details.moderator ? txt( details.moderator.lastname)+', '+txt(details.moderator.firstname)+' '+txt(details.moderator.infix) : null }
                            </option>
                        {/if}
                    </Select>
                {#if edit && changed}
                    <Button class='edit' on:click={onSubmit} label='' value='speichern' />
                {/if}
            {/if}
        </form>
    {/if}
</div>

<style>
    .edit {
        @apply cursor-pointer;
    }
    select {
        background: green;
    }
</style>