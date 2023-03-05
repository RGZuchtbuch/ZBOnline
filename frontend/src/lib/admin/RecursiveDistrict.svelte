<script>
    import api from '../../js/api.js';
    import {txt} from '../../js/util.js';
    import Button from '../input/Button.svelte';
    import Number from '../input/Number.svelte';
    import Select from '../input/Select.svelte';
    import Text from '../input/Text.svelte';

    export let district = null;
    let details = null;
    let breeders = null;

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
                api.district.breeders.get(district.id).then(response => {
                    breeders = response.breeders;
                })
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
            edit:false, // to make it show
        });
        district.open = true; // show children including this one
        district = district; // redraw
    }

    function onChange( event ) {
        district.changed = true;
        district = district;
    }

    function onSubmit() {
        console.log( 'Submit district', district );
        api.district.post( details ).then( response => {
           details.id = response.id;
           district.id = response.id;
           district.name = district.detail.name;
        });
        district.changed = false;
    }

</script>

<div on:click class='pl-8'>
    <div>&#10551; {district.name}
        {#if district.children.length > 0}
            {#if district.open}
                <span class='open' on:click={onOpen}>[ - ]</span>
            {:else}
                <span class='open' on:click={onOpen}>[ + ]</span>
            {/if}
        {/if}
        {#if district.edit}
            <span class='button text-red-600' on:click={onEdit} title='schließen'>[ &#9998; ]</span>
        {:else}
            <span class='button text-green-600' on:click={onEdit} title='bearbeiten'>[ &#9998; ]</span>
        {/if}

    </div>

    {#if district.edit }
        <form class='flex flex-col border border-gray-400 rounded m-4 p-2' on:change={onChange}>
            {#if details }
                <Text class='w-64' bind:value={details.name} label='Name' required/>

                <Text class='w-128' bind:value={details.fullname} label='Name voll' required/>
                <Text class='w-24' bind:value={details.short} label='Name abk.' required/>
                <div class='flex gap-x-2'>
                    <Number class='w-32' bind:value={details.latitude} label='Breitegrad N]' min={MINLATITUDE} max={MAXLATITUDE} required/>
                    <Number class='w-32' bind:value={details.longitude}  label='Längegrad O' min={MINLONGITUDE} max={MAXLONGITUDE} required/>
                </div>
                {#if district.id > 0}
                    <div class='flex gap-x-2'>
                        <Number class='w-24' value={district.children.length} label='U. Verbände' disabled/>
                        <Button class='edit' on:click={onAddChild} label='' value='hinzufügen' />
                    </div>
                {/if}

                <Select class='' label='Obmann' bind:value={details.moderatorId} >
                    {#if breeders}
                        {#each breeders as candidate}
                            <option class='bg-white' value={candidate.id} selected={candidate.id == details.moderatorId}> {candidate.lastname}, {candidate.firstname} {txt(candidate.infix)} </option>
                        {/each}
                    {/if}
                </Select>

                {#if district.changed}
                    <Button class='edit' on:click={onSubmit} label='' value='speichern' />
                {/if}
            {/if}
        </form>
    {/if}
    <div></div>

    {#if district.open}
        {#each district.children as child}
            <svelte:self district={child} />
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
</style>