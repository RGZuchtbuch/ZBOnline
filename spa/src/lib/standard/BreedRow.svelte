<script>
    import api from '../../js/api.js';
    import { user } from '../../js/store.js';
    import ColorRow from './ColorRow.svelte';

    import Button from '../input/Button.svelte';
    import NumberInput from '../input/Number.svelte';
    import TextInput from '../input/Text.svelte';

    export let breed = null;

    let open = false;
    let details = breed && breed.id ? false : true; // show details if new
    let edit = false;
    let changed = false;

    function onOpen() {
        open = ! open;
    }

    function onAddColor() {
        if( breed ) {
            console.log('Add Color');
            let newColor = { id:null, name:'', breedId:breed.id, aoc:null, info:null }
            breed.colors.splice(0, 0, newColor); // insert as first
            breed = breed;
        }
    }

    function onDetails() {
        console.log( 'Details', details );
        details = ! details;
    }

    function onEdit() {
        console.log( 'Edit', edit );
        edit = ! edit;
    }

    function onChange() {
        changed = true;
        console.log( 'Changed' );
    }

    function onSubmit() {
        changed = false;
        edit = false;
        api.breed.post( breed ).then( response => {
            if( ! breed.id ) { // new
                breed.id = response.id;
            }
            console.log( 'Response', response );
        })
        console.log( 'Submit' );
    }

    console.log( 'Breed', breed );

</script>

<div class='flex flex-col pl-8'>
    <div class='flex flex-row border-b border-gray-300 px-4 gap-x-1'>
        <div class='w-12 text-xs'># {breed.id}</div>

        <div class='grow cursor-pointer' on:click={onOpen}>
            {breed.name} ({breed.colors.length})
        </div>
        <div class='w-8 cursor-pointer' on:click={onDetails} title='Details'>[ D ]</div>
        {#if $user && $user.admin}
            <div class='w-8 cursor-pointer' on:click={onAddColor} title='Farbe hinzufügen'>[ + ]</div>
        {/if}
    </div>

    {#if details}
        <form on:change={onChange} on:submit|preventDefault={onSubmit}>
            <div class='flex flex-row pl-16'>
                <div class='grow'><TextInput class='w-64' label={'Name der Rasse'} bind:value={breed.name} disabled={! edit}/></div>
                {#if $user && $user.admin}
                    <div class='w-8 cursor-pointer' on:click={onEdit} title='Edit'>[ E ]</div>
                {/if}
            </div>
            <div class='flex flex-row pl-16 gap-x-2'>
                <NumberInput class='w-24' label='1.0 Ring' bind:value={breed.sireRing} disabled={! edit}/>
                <NumberInput class='w-24' label='0.1 Ring' bind:value={breed.dameRing} disabled={! edit}/>
            </div>
            <div class='flex flex-row pl-16 gap-x-2 '>
                <NumberInput class='w-24' label='1.0 Gewicht' bind:value={breed.sireWeight} disabled={! edit}/>
                <NumberInput class='w-24' label='0.1 Gewicht' bind:value={breed.dameWeight} disabled={! edit}/>
            </div>

            {#if changed}
                <div class='flex flex-row'>
                    <div class='grow'></div>
                    <button class='bg-green-100 px-2 text-green-700' type='submit'>&#10004;</button>
                </div>
            {/if}
        </form>
    {/if}

    {#if open}
        {#each breed.colors as color }
            <ColorRow color={color}/>
        {/each}
    {/if}
</div>
