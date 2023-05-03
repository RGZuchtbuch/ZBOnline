<script>
    import api from '../../js/api.js';
    import { user } from '../../js/store.js';
    import ColorRow from './ColorRow.svelte';

    import Button from '../common/input/Button.svelte';
    import NumberInput from '../common/input/Number.svelte';
    import TextInput from '../common/input/Text.svelte';
    import TextArea from '../common/input/TextArea.svelte';

    export let breed = null;
    let details = null;

    let open = false;
    let showDetails = breed && breed.id ? false : true; // show details if new
    let edit = false;
    let changed = false;

    function onOpen() {
        open = ! open;
    }

    function onAddColor() {
        if( breed ) {
            console.log('Add Color');
            let newColor = { id:null, name:'Neu', breedId:breed.id, aoc:null, info:null }
            breed.colors.splice(0, 0, newColor); // insert as first
            breed = breed;
        }
    }

    function onDetails() {
        console.log( 'Breed', breed );
        if( breed ) {
            if( breed.id === null ) {
                details = Object.assign( {}, breed );
            } else {
                api.breed.get(breed.id).then(response => {
                    details = response.breed;
                    console.log(details);
                });
            }
        } else {
            console.log( 'Invalid breed, should not happen');
        }
        showDetails = ! showDetails;
        console.log( 'Details', showDetails );
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
//        edit = false;
        breed.name = details.name;
        api.breed.post( details ).then( response => {
            if( ! details.id ) { // new
                details.id = response.id;
                breed.id = response.id;
            }
            console.log( 'Response', response );
        })
        console.log( 'Submit' );
    }
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

    {#if showDetails && details}
        <form class='ml-16 p-2 border border-gray-600' on:change={onChange} on:submit|preventDefault={onSubmit}>

            <div class='flex flex-row'>
                <div class='grow'><TextInput class='w-64' label={'Name der Rasse'} bind:value={details.name} disabled={! edit}/></div>
                {#if $user && $user.admin}
                    <div class='w-8 cursor-pointer' on:click={onEdit} title='Edit'>[ E ]</div>
                {/if}
            </div>
            <div class='flex flex-row gap-x-2'>
                <NumberInput class='w-24' label='1.0 Ring' bind:value={details.sireRing} disabled={! edit}/>
                <NumberInput class='w-24' label='0.1 Ring' bind:value={details.dameRing} disabled={! edit}/>
            </div>
            <div class='flex flex-row gap-x-2 '>
                <NumberInput class='w-24' label='1.0 Gewicht' bind:value={details.sireWeight} disabled={! edit}/>
                <NumberInput class='w-24' label='0.1 Gewicht' bind:value={details.dameWeight} disabled={! edit}/>
            </div>

            {#if edit}
                <TextArea label='Edit Info in html' bind:value={details.info} disabled={!edit} />
            {/if}

            <div class='flex flex-col'>
                <span class='label'>Info</span>
                <div class=''>{@html details.info}</div>
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
