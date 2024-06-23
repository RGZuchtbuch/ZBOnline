<script>
    import { createEventDispatcher } from 'svelte';
    import { slide } from 'svelte/transition';
    import {meta} from "tinro";
    import api from '../../js/api.js';
    import dic from '../../js/dictionairy.js';
    import { broodGroups, user } from '../../js/store.js';
    import { dec } from '../../js/util.js';
    import validator from '../../js/validator.js';

    import Form from '../common/form/Form.svelte';
    import FormStatus from '../common/form/Status.svelte';
    import CheckBoxInput from '../common/form/input/CheckBoxInput.svelte';
    import NumberInput from '../common/form/input/NumberInput.svelte';
    import TextInput from '../common/form/input/TextInput.svelte';
    import Select from '../common/form/input/Select.svelte';

    import ColorRow from './ColorRow.svelte';
    import Toggler from '../common/OpenClose.svelte';

    export let section;
    export let breed;

    export let open = false;

    let edit = false;

    let toRemove = false;

    const route = meta();
    const dispatch = createEventDispatcher();

    const validate = { // for validating Form fields
        //name:       (v) => validator(v).string().length(2,128).orNull().isValid(),
        name:       (v)=> validator(v).string().length(2,128).orNullIf( toRemove ).isValid(),
        eggs:       (v) => validator(v).number().range(1,366).orNull().isValid(),
        eggWeight:  (v) => validator(v).number().range(1,9999).orNull().isValid(),
        weight:     (v) => validator(v).number().range(1,99999).orNull().isValid(),
        ring:       (v) => validator(v).number().range(1,99).orNull().isValid(),
    }

    function  onAddColor() {
        if( breed && breed.id ) {
            const color = {id: 0, breedId: breed.id, name: 'Farbe', info: null, aoc: false};
            breed.colors = [color, ...breed.colors]; // add default
            open = true;
        }
    }
    function onEdit() {
        edit = ! edit;
        open = true;
    }
    function onOpen() {
        dispatch( 'open', breed );
    }

    function onSubmit() { // triggered by form's autosave
        console.log( 'Submit breed' );
        if( breed.name ) {
            if( breed.id > 0 ) {
                api.breed.put( breed.id, breed );
            } else {
                api.breed.post( breed ).then(response => {
                    breed.id = response.id; // in case of new id
                });
            }
        } else { // should remove, hmm take care
            if( toRemove && breed.id > 0 && breed.name == null && breed.colors.length === 0) { // stored breed and no colors
                edit = false;
                api.breed.delete( breed.id ).then( response => {
                    breed.id = null;
                    dispatch( 'removed', breed );
                } )
            }
        }
    }

    function onColorRemoved( event ) {
        const color = event.detail;
        console.log( 'Color Removed', color );
        const index = breed.colors.indexOf( color ); // find it
        if( index >= 0 ) { // found
            console.log( 'Color index', index );
            breed.colors.splice(index, 1); // remove it
            breed = breed; // trigger
        }
    }

</script>

<div class='flex flex-col pl-6' transition:slide>
    {#if breed}
        <div class='flex flex-row gap-x-1 border-b border-gray-300 my-1'>
            <Toggler bind:open={open} enabled={breed.colors.length > 0} class='text-orange-600'/>

            <div class='font-semibold' title={dic.title.breed}>
                {breed.name}
            </div>
            <div class='text-xs' title={dic.title.colors} >({breed.colors.length})</div>

            <div class='grow flex gap-x-2 justify-end text-sm italic'>
                {#if section.layers}
                    <div class='flex'>
                        <div class='w-8 text-right'>{#if breed.layEggs} {dec(breed.layEggs)} {/if}</div>
                        <small class='w-6 self-center text-center'>{#if breed.layEggs} e/j {/if}</small>

                        <div class='w-8 text-right'>{#if breed.layWeight} {dec(breed.layWeight)} {/if}</div>
                        <small class='w-6 self-center text-center'>{#if breed.layWeight} g {/if}</small>
                    </div>
                {:else}
                    <div class='flex '>
                        <div class='w-28 text-center'>{dec(breed.broodGroup)}</div>
                    </div>
                {/if}
                <div class='flex border-0'>
                    <div class='w-12 text-right'> {#if breed.sireWeight} {dec(breed.sireWeight)}{/if} </div>
                    <small class='w-2 self-center text-center'>{#if breed.sireWeight} . {/if}</small>
                    <div class='w-12 text-left'> {#if breed.dameWeight} {dec(breed.dameWeight)} {/if} </div>
                    <small class='w-2 text-center self-center '>{#if breed.sireWeight || breed.dameWeight }g{/if}</small>
                </div>
                <div class='flex'>
                    <div class='w-6 text-right'>{#if breed.sireRing}{dec(breed.sireRing)} {/if}</div>
                    <small class='w-2 self-center text-center'>{#if breed.sireRing} . {/if}</small>
                    <div class='w-6 text-left'>{#if breed.dameRing} {dec(breed.dameRing)} {/if}</div>
                    <small class='w-6 self-center text-center'>{#if breed.sireRing || breed.dameRing} mm {/if}</small>
                </div>
            </div>

            {#if $user && $user.admin }
                <div class='flex text-xs text-red-600'>
                    <button class='w-4 border-0' type='button' on:click={onEdit} title='Rasse ändern'>
                        <img src="assets/edit.svg" alt="Rassename ändern">
                    </button>
                    {#if open || breed.colors.length === 0 }
                        <button class='w-4 border-0' type='button' on:click={onAddColor} title='Farbe hinzufügen'>
                            <img src="assets/add.svg" alt="Farbe hinzufügen">
                        </button>
                    {:else}
                        <div class='w-4'></div>
                    {/if}
                </div>
            {/if}

            <small class='w-6 text-gray-400 text-3xs text-right cursor-auto' title={breed.id}>[{breed.id}]</small>
        </div>

        {#if edit}
            <div class='pl-6 border-2 border-red-400 rounded' transition:slide>
                <Form class='flex flex-row gap-1' on:submit={onSubmit}>
                        <TextInput class='grow' label='Farbe' bind:value={breed.name} error='pflichtfeld' validator={validate.name} />

                    {#if section.layers }
                        <NumberInput class='w-12' bind:value={breed.layEggs} label='Eier' error='pflichtfeld' validator={validate.eggs} />
                        <NumberInput class='w-12' bind:value={breed.layWeight} label='Gew.' error='pflichtfeld' validator={validate.eggWeight} />
                    {:else}
                        <Select class='w-10' bind:value={breed.broodGroup} label={dic.label.broodgroup}>
                            {#each $broodGroups as group }
                                <option value={group.id}>{group.name}</option>
                            {/each}
                        </Select>
                    {/if}
                    <NumberInput class='w-14' bind:value={breed.sireWeight} label='Gew 1.0' error='pflichtfeld' validator={validate.weight} />
                    <NumberInput class='w-14' bind:value={breed.dameWeight} label='Gew 0.1' error='pflichtfeld' validator={validate.weight} />

                    <NumberInput class='w-10' bind:value={breed.sireRing} label='Ring 1.0' error='1..99' validator={validate.ring} />
                    <NumberInput class='w-10' bind:value={breed.dameRing} label='Ring 0.1' error='1..99' validator={validate.ring} />

                    <CheckBoxInput class='w-10' label='Löschen' bind:value={toRemove} title={dic.title.delete.breed} disabled={ breed.name != null || breed.colors.length > 0}/>
                    <FormStatus class='w-6' />
                </Form>
            </div>
        {/if}


        {#if open}
            <div class='flex flex-col' transition:slide>
                {#each breed.colors as color (color.id) }
                    <ColorRow bind:breed={breed} {color} on:removed={onColorRemoved}/>
                {/each}
            </div>
        {/if}
    {/if}
</div>

<style>

</style>
