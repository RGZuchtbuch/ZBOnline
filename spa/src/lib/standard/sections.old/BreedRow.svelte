<script>
    import api from '../../../js/api.js';
    import { user } from '../../../js/store.js';
    import ColorRow from './ColorRow.svelte';

    import Button from '../../common/input/Button.svelte';
    import NumberInput from '../../common/input/Number.svelte';
    import TextInput from '../../common/input/Text.svelte';
    import TextArea from '../../common/input/TextArea.svelte';
    import BreedDetails from "./BreedDetails.svelte";

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
            let newColor = { id:null, name:'Neu', breedId:breed.id, aoc:null, info:null }
            breed.colors.splice(0, 0, newColor); // insert as first
            breed = breed;
        }
    }

    function onDetails() {
        if( details ) {
            details = null;
        } else {
            loadDetails();
        }
    }

    function onChange() {
        changed = true;
    }

    function onSubmit() {
        changed = false;
        breed.name = details.name;
        api.breed.create( details ).then(response => {
            if( ! details.id ) { // new
                details.id = response.id;
                breed.id = response.id;
            }
        })
    }

    function loadDetails() {
        if( breed ) {
            if( breed.id === null ) { // new
                details = Object.assign( {}, breed ); // get from new breed
            } else {
                api.breed.get(breed.id).then( response => { details = response.breed; } );
            }
        } else {
            console.log( 'Error: Invalid breed, should not happen' );
        }
    }
</script>

<div class='flex flex-col pl-8'>
    <div class='flex flex-row border-b border-gray-300 px-2 gap-x-1'>
        <div class='w-12 text-xs'># {breed.id}</div>

        <div class='grow cursor-pointer' on:click={onOpen}>
            {breed.name} ({breed.colors.length})
        </div>
        <div class='w-8 cursor-pointer' on:click={onDetails} title='Details'>[ D ]</div>
        {#if $user && $user.admin && open}
            <div class='w-8 cursor-pointer' title='Neue Farbe' on:click={onAddColor}>[ + ]</div>
        {/if}
    </div>

    {#if details}
        <BreedDetails breed={breed} />
    {/if}

    {#if open}
        {#each breed.colors as color }
            <ColorRow color={color}/>
        {/each}
    {/if}
</div>
