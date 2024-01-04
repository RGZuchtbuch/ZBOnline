<script>
    import { slide } from 'svelte/transition';
    import { meta } from 'tinro';
    import { user } from '../../js/store.js';
    import dic from '../../js/dictionairy.js';
    import validator from '../../js/validator.js';
    import api from "../../js/api.js";

    import Form from '../common/form/Form.svelte';
    import TextInput from '../common/form/input/TextInput.svelte';
    import FormStatus from '../common/form/Status.svelte';

    export let breed;
    export let color = null;

    let edit = false;
    let changed = false;

    let route = meta();

    if( color === null ) color = { id:0, breedId:breed.id, name:'?', info:null, aoc:false }; // default is null

    function onChange() {
        changed = true;
        console.log( 'Changed' );
    }

    const validate = {
        name: (v)=> validator(v).string().length(2,128).orNull().isValid(),
    }

    function onEdit() {
        edit = ! edit;
    }

    function onSubmit() {
        //edit = false;
        if( color.name ) {
            api.color.post( color ).then( response => {
                color.id = response.id; // in case of new id
            })
        } else { // should remove, hmm take care
            if( color.id ) {
                api.color.delete(color.id).then( response => {
                    if( response.success ) {

                    }
                } )
            }
        }
        console.log( 'Submit color' );
    }

</script>

<div class='flex flex-col pl-8'>

    <div class='flex flex-row border-b border-gray-300 gap-x-1 my-1'>
        <div class='w-4'>⤷</div>
        {#if color.name}
            <div class='grow cursor-pointer'>
                {color.name}
            </div>
        {:else}
            <div title={dic.title.deleted}>
                ---------------
            </div>
        {/if}

        <div class='grow'></div>
        <div class='w-12 text-xs'>[{color.id}]</div>
        {#if $user && $user.admin }
            <button type='button' on:click={onEdit} title='Farbendaten ändern'> (e) </button>
        {/if}

    </div>
    {#if edit}
        <div class='pl-4' transition:slide>
            <Form class='flex flex-col' on:submit={onSubmit}>
                <div class='flex'>
                    <div>Farbe ändern. leerlassen ist löschen</div>
                    <FormStatus />
                </div>
                <TextInput class='w-128' label='Farbe' bind:value={color.name} error='pflichtfeld' validator={validate.name} />
            </Form>
        </div>
    {/if}

</div>

