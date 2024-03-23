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
        if( breed.name ) {
            if( breed.id > 0 ) {
                api.breed.put( breed.id, breed );
            } else {
                api.breed.post( breed ).then(response => {
                    breed.id = response.id; // in case of new id
                });
            }
        } else { // should remove, hmm take care
            if( breed.id && breed.name == null && toRemove && breed.colors.length === 0) { // stored breed and no colors
                edit = false;
                api.breed.delete( breed.id ).then( response => {
                    dispatch( 'removed', breed );
                } )
            }
        }
        console.log( 'Submit breed' );
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
    console.log( 'Section', section );

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
                <div class='flex w-8 text-xs text-red-600'>
                    <button class='w-4' type='button' on:click={onEdit} title='Rassedaten ändern'> (e) </button>
                    {#if open || breed.colors.length === 0 }
                        <button class='w-4' type='button' on:click={onAddColor} title='Farbe hinzufügen'> (✚) </button>
                    {/if}
                </div>
            {/if}

            <small class='w-6 text-gray-400 text-3xs text-right cursor-auto' title={breed.id}>[{breed.id}]</small>
        </div>

        {#if edit}
            <div class='pl-6 border-2 border-red-400 rounded' transition:slide>
                <Form class='flex flex-col' on:submit={onSubmit}>
                    <div class='flex'>
                        <div>Rasse ändern. leer & löschen nur wenn ohne Farbenschläge</div>
                        <FormStatus />
                    </div>
                    <div class='flex'>
                        <TextInput class='w-128' label='Farbe' bind:value={breed.name} error='pflichtfeld' validator={validate.name} />
                        <div class='grow'></div>
                        <CheckBoxInput class='w-12' label='Löschen' bind:value={toRemove} disabled={ breed.name != null || breed.colors.length > 0}/>
                    </div>
                    {#if section.layers }
                        <div class='flex'>
                            <NumberInput class='w-32' bind:value={breed.layEggs} label='Eier/Jahr' error='pflichtfeld' validator={validate.eggs} />
                            <NumberInput class='w-32' bind:value={breed.layWeight} label='Ei gewicht' error='pflichtfeld' validator={validate.eggWeight} />
                        </div>
                    {:else}
                        <div class='flex'>
                            <Select bind:value={breed.broodGroup} label={dic.label.broodgroup}>
                                {#each $broodGroups as group }
                                    <option value={group.id}>{group.name}</option>
                                {/each}
                            </Select>
                        </div>
                    {/if}
                    <div class='flex'>
                        <NumberInput class='w-32' bind:value={breed.sireWeight} label='Gewicht Hahn' error='pflichtfeld' validator={validate.weight} />
                        .
                        <NumberInput class='w-32' bind:value={breed.dameWeight} label='Gewicht Henne' error='pflichtfeld' validator={validate.weight} />
                    </div>
                    <div class='flex'>
                        <NumberInput class='w-32' bind:value={breed.sireRing} label='Ring Hahn' error='1..99' validator={validate.ring} />
                        .
                        <NumberInput class='w-32' bind:value={breed.dameRing} label='Ring Henne' error='1..99' validator={validate.ring} />
                    </div>
                </Form>
            </div>
        {/if}


        {#if open}
            <div class='flex flex-col' transition:slide>
                {#each breed.colors as color }
                    <ColorRow bind:breed={breed} {color} on:removed={onColorRemoved}/>
                {/each}
            </div>
        {/if}
    {/if}
</div>
