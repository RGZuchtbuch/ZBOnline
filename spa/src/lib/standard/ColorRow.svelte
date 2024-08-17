<script>
    import {createEventDispatcher} from 'svelte';
    import { slide } from 'svelte/transition';
    import { meta } from 'tinro';
    import { user } from '../../js/store.js';
    import dic from '../../js/dictionairy.js';
    import validator from '../../js/validator.js';
    import api from "../../js/api.js";
    import { txt } from '../../js/util.js';

    import Form from '../common/form/Form.svelte';
    import TextInput from '../common/form/input/TextInput.svelte';
    import FormStatus from '../common/form/Status.svelte';
    import CheckBoxInput from '../common/form/input/CheckBoxInput.svelte';

    export let breed;
    export let color = null;

    let edit = false;
    let changed = false;
    let toRemove = false;

    const dispatch = createEventDispatcher();
    let route = meta();

    //if( color === null ) color = { id:0, breedId:breed.id, name:'?', info:null, aoc:false }; // default is null

    const validate = {
        name: (v)=> validator(v).string().length(2,128).orNullIf( toRemove ).isValid(),
    }

    function onEdit() {
        edit = ! edit;
    }

    function onSubmit() {
        console.log( 'Submit ', color );
        if( color.name ) {
            toRemove = false;
            if( color.id > 0 ) { // not null and not null
                api.color.put( color.id, color ).then(response => {
                    // nothing to do, really
                })
            } else { // new
                api.color.post( color ).then(response => {
                    color.id = response.id; // in case of new id
                })
            }
        } else if( toRemove && color.id > 0 && color.name == null ) {
            api.color.delete(color.id).then( response => {
                if( response.success ) {
                    color.id = null;
                    edit = false;
                    dispatch( 'removed', color );
                } else {
                    toRemove = false;
                }
            } )
        }
    }


</script>

<div class='flex flex-col pl-8' transition:slide>
    {#if color}
        <div class='flex flex-row border-b border-gray-300 gap-x-1 my-1'>
            <div class='w-0'></div>

            <div class='grow cursor-auto' title={dic.title.color}>
                ⤷ {txt(color.name)}
            </div>

            <div class='flex text-xs text-red-600'>
                {#if $user && $user.admin }
                    <button class='w-4 border-0' type='button' on:click={onEdit} title={dic.title.change}>
                        <img src="assets/edit.svg" alt="Farbe ändern">
                    </button>
                    <div class='w-4'></div>
                {/if}
            </div>

            {#if $user && $user.admin}
                <small class='w-6 text-gray-400 text-3xs text-right cursor-auto' title={color.id}>[{color.id}]</small>
            {/if}
        </div>

        {#if edit}
            <div class='pl-2 border-2 border-red-400 rounded' transition:slide>
                <Form class='flex flex-col' on:submit={onSubmit}>
                    <div class='flex'>
                        <TextInput class='w-128' label='Farbe' bind:value={color.name} error='pflichtfeld' validator={validate.name} />
                        <div class='grow'/>
                        <CheckBoxInput class='w-10' label='Löschen' bind:value={toRemove} title={dic.title.delete.color} disabled={ color.name != null }/>
                        <FormStatus class='w-6'/>
                    </div>
                </Form>
            </div>
        {/if}
    {/if}
</div>

