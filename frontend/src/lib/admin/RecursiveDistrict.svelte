<script>
    import api from '../../js/api.js';
    import Button from '../input/Button.svelte';
    import Number from '../input/Number.svelte';
    import Text from '../input/Text.svelte';
    export let district = null;

    function onOpen() {
        district.open = ! district.open;
    }

    function onEdit() {
        district.edit = ! district.edit;
        if( district.edit ) {
            api.district.get(district.id).then(response => {
                district.detail = response.district;
                district = district;
                console.log('Got Details', district);
            });
        }
    }

    function onAddChild() {
        console.log( 'Add Child' );
        district.children.splice( 0, 0, {
            id:null, parentId:district.id, name:'Neu', children: [],
            detail: {
                id:null, parentId:district.id, name: null, fullname: null, short: null, latitude: null, longitude: null,
            },
            edit:true, // to make it show
        });
        district.open = true; // show children including this one
        district = district; // redraw
    }

    function onChange( event ) {
        district.changed = true;
        district = district;
    }

    function onSubmit() {
        console.log( 'New district', district );
        api.district.post( district.detail ).then( response => {
           district.id = response.id;
           district.detail.id = district.id;
           district.name = district.detail.name;
        });
        district.changed = false;
    }

</script>

<div on:click class='pl-6'>
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
            {#if district.detail }
                <Text class='w-64' bind:value={district.detail.name} label='Name' required/>

                <Text class='w-128' bind:value={district.detail.fullname} label='Name voll' required/>
                <Text class='w-24' bind:value={district.detail.short} label='Name abk.' required/>
                <div class='flex gap-x-2'>
                    <Number class='w-32' bind:value={district.detail.latitude} label='Breitegrad' min={MINLATITUDE} max={MAXLATITUDE} required/>
                    <Number class='w-32' bind:value={district.detail.longitude}  label='Längegrad' min={MINLONGITUDE} max={MAXLONGITUDE} required/>
                </div>
                {#if district.id > 0}
                    <div class='flex gap-x-2'>
                        <Number class='w-24' value={district.children.length} label='U. Verbände' disabled/>
                        <Button class='edit' on:click={onAddChild} label='' value='hinzufügen' />
                    </div>
                {/if}
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
</style>