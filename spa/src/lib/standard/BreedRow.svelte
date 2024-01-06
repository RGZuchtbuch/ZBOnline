<script>
    import { createEventDispatcher } from 'svelte';
    import { slide } from 'svelte/transition';
    import {meta} from "tinro";
    import api from '../../js/api.js';
    import { user } from '../../js/store.js';
    import { dec } from '../../js/util.js';
    import validator from '../../js/validator.js';

    import Form from '../common/form/Form.svelte';
    import FormStatus from '../common/form/Status.svelte';
    import NumberInput from '../common/form/input/NumberInput.svelte';
    import TextInput from '../common/form/input/TextInput.svelte';

    import ColorRow from './ColorRow.svelte';

    export let breed;

    export let open = false;

    let edit = false;

    const route = meta();
    const dispatch = createEventDispatcher();

    const validate = { // for validating Form fields
        name:       (v) => validator(v).string().length(2,128).orNull().isValid(),
        eggs:       (v) => validator(v).number().range(1,366).orNull().isValid(),
        eggWeight:  (v) => validator(v).number().range(1,9999).orNull().isValid(),
        weight:     (v) => validator(v).number().range(1,99999).orNull().isValid(),
        ring:       (v) => validator(v).number().range(1,99).orNull().isValid(),
    }

    function  onAddColor() {
        const color = { id:0, breedId:breed.id, name:'Farbe', info:null, aoc:false };
        breed.colors = [ color, ...breed.colors ]; // add default
        breed.open = true;
    }
    function onEdit() {
        edit = ! edit;
    }
    function onOpen() {
        dispatch( 'open', breed );
    }

    function onSubmit() { // triggered by form's autosave
        //edit = false;
        if( breed.name ) {
            api.breed.post( breed ).then( response => {
                breed.id = response.id; // in case of new id
            })
        } else { // should remove, hmm take care
            if( breed.id ) {
                api.breed.delete( breed.id ).then( response => {
                    if( response.success ) {

                    }
                } )
            }
        }
        console.log( 'Submit breed' );
    }

</script>

<div class='flex flex-col pl-8' transition:slide>
    <div class='flex flex-row gap-x-1 border-b border-gray-300 my-1'>
        <div class='w-4'>⤷</div>
        <button type='button' class='font-semibold cursor-pointer' on:click={onOpen}>
            {breed.name} ({breed.colors.length})
        </button>
        <div class='grow px-2 flex gap-x-2 justify-end italic'>

            <div class='flex gap-x-1'>
                <div class='w-16 text-right'>{#if breed.layEggs}{dec(breed.layEggs)} <small>e/j</small>{/if}</div>
                <div class='w-16 text-right'>{#if breed.layEggs}{dec(breed.layWeight)} <small>g</small>{/if}</div>
            </div>
            <div class='flex gap-x-1'>
                <div class='w-12 text-right'>{#if breed.sireWeight}{dec(breed.sireWeight)}{/if}</div>.
                <div class='w-16 text-left'>{#if breed.dameWeight}{dec(breed.dameWeight)} <small>g</small>{/if}</div>
            </div>
            <div class='flex gap-x-1'>
                <div class='w-12 text-right'>{#if breed.sireRing}{dec(breed.sireRing)}{/if}</div>.
                <div class='w-16 text-left'>{#if breed.dameRing}{dec(breed.dameRing)} <small>mm</small>{/if}</div>
            </div>
        </div>
        <small class='w-12 text-2xs'>[{breed.id}]</small>

        {#if $user && $user.admin }
            <button type='button' on:click={onEdit} title='Rassedaten ändern'> (e) </button>
            <button type='button' on:click={onAddColor} title='Farbe hinzufügen'> (✚) </button>
        {/if}
        <div class='w-4 hidden'> <a href={route.match+'/sparte/'+breed.sectionId+'/rasse/'+breed.id}> [+]s </a> </div>
    </div>

    {#if edit}
        <div class='pl-4' transition:slide>
            <Form class='flex flex-col' on:submit={onSubmit}>
                <div class='flex'>
                    <div>Rasse ändern. name leerlassen heist löschen</div>
                    <FormStatus />
                </div>
                <TextInput class='w-128' label='Farbe' bind:value={breed.name} error='pflichtfeld' validator={validate.name} />
                <div class='flex'>
                    <NumberInput class='w-32' bind:value={breed.layEggs} label='Eier/Jahr' error='pflichtfeld' validator={validate.eggs} />
                    <NumberInput class='w-32' bind:value={breed.layWeight} label='Ei gewicht' error='pflichtfeld' validator={validate.eggWeight} />
                </div>
                <div class='flex'>
                    <NumberInput class='w-32' bind:value={breed.sireWeight} label='Gewicht Hahn' error='pflichtfeld' validator={validate.weight} />
                    .
                    <NumberInput class='w-32' bind:value={breed.dameWeight} label='Gewicht Henne' error='pflichtfeld' validator={validate.weight} />
                </div>
                <div class='flex'>
                    <NumberInput class='w-32' bind:value={breed.sireRing} label='Eier/Jahr' error='pflichtfeld' validator={validate.ring} />
                    .
                    <NumberInput class='w-32' bind:value={breed.dameRing} label='Ei gewicht' error='pflichtfeld' validator={validate.ring} />
                </div>
            </Form>
        </div>
    {/if}


    {#if open}
        <div class='flex flex-col' transition:slide>
            {#each breed.colors as color }
                <ColorRow {breed} {color}/>
            {/each}
        </div>
    {/if}
</div>
