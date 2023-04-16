<script>
    import api from '../../js/api.js';
    import Breed from './Breed.svelte';
    import Button from '../input/Button.svelte';
    import Number from '../input/Number.svelte';
    import Select from '../input/Select.svelte';
    import Text from '../input/Text.svelte';

    export let section = null;

    let breeds = null;

    function onOpen() {
        section.open = ! section.open;
    }

    function onBreeds() {
        console.log( 'OnBreeds');
        if( breeds ) {
            breeds = null;
        } else {
            api.section.breeds.get( section.id).then( response => {
                breeds = response.breeds;
            });
        }
    }


    function onEdit() {
        section.edit = ! section.edit;
        if( section.edit ) {
            api.section.get(section.id).then(response => {
                section.detail = response.section;
                section = section;
                console.log('Got Details', section);
            });
        }
    }

    function onAddChild() {
        console.log( 'Add Child' );
        section.children.splice( 0, 0, {
            id:null, parentId:section.id, name:'Neu', layers:null, order:null, children: [],
            edit:true, // to make it show
        });
        section.open = true; // show children including this one
        section = section; // redraw
    }

    function onChange() {
        section.changed = true;
        section = section;
    }

    function onSubmit() {
        section.changed = false;
        console.log( 'New section', section );
        api.section.post( section ).then( response => {
            section.id = response.id;
        });
    }

</script>

<div on:click class='pl-6'>
    <div>&#10551; {section.name}
        {#if section.children.length > 0}
            {#if section.open}
                <span class='open' on:click={onOpen}>[ - ]</span>
            {:else}
                <span class='open' on:click={onOpen}>[ + ]</span>
            {/if}
        {/if}

        {#if breeds}
            <span class='button text-red-600' on:click={onBreeds}>[ &#8628; ]</span>
        {:else}
            <span class='button text-green-600' on:click={onBreeds}>[ &#8628; ]</span>
        {/if}


        {#if section.edit}
            <span class='button text-red-600' on:click={onEdit} title='schließen'>[ &#9998; ]</span>
        {:else}
            <span class='button text-green-600' on:click={onEdit} title='bearbeiten'>[ &#9998; ]</span>
        {/if}
    </div>

    {#if section.edit }
        <form class='flex flex-col border border-gray-400 rounded m-4 p-2' on:change={onChange}>
            <Text class='w-64' bind:value={section.name} label='Name' required/>
            <Select bind:value={section.layers} label='Legeleistung'>
                <option value='Y'>Y</option>
                <option value='N'>N</option>
            </Select>
            {#if section.id > 0}
                <div class='flex gap-x-2'>
                    <Number class='w-24' value={section.children.length} label='U. Sparten' disabled/>
                    <Button class='edit' on:click={onAddChild} label='' value='hinzufügen' />
                </div>
            {/if}
            {#if section.changed}
                <Button class='edit' on:click={onSubmit} label='' value='speichern' />
            {/if}
        </form>
    {/if}

    {#if breeds}
        {#each breeds as breed}
            <Breed breed={breed} />
        {/each}
    {/if}

    {#if section.open}
        {#each section.children as child}
            <svelte:self section={child} />
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