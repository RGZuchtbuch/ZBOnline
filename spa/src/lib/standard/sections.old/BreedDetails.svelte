<script>
    import api from '../../../js/api.js';
    import { user } from '../../../js/store.js';
    import ColorRow from './ColorRow.svelte';

    import Button from '../../common/input/Button.svelte';
    import NumberInput from '../../common/input/Number.svelte';
    import TextInput from '../../common/input/Text.svelte';
    import TextArea from '../../common/input/TextArea.svelte';

    export let breed = null;
    let details = null;

    let edit = false;
    let changed = false;

    function onEdit() {
        edit = ! edit;
    }

    function onChange() {
        changed = true;
    }

    function onSubmit() {
        changed = false;
        breed.name = details.name;
        api.breed.post( details ).then(response => {
            if( ! details.id ) { // new
                details.id = response.id;
                breed.id = response.id;
            }
        })
    }

    function loadBreed( id ) {
        if( id ) {
            api.breed.get(breed.id).then( response => {
                details = response.breed;
            });
        } else { // id = null so new breed
            details = Object.assign( {}, breed ); // get from new breed
        }
    }

    $: loadBreed( breed.id );

</script>


<div class='flex flex-col pl-8'>
    {#if details}

        <form class='ml-4 p-2 border border-gray-600' on:change={onChange} on:submit|preventDefault={onSubmit}>

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
            <div class='h-4'></div>

            {#if changed}
                <div class='flex flex-row'>
                    <div class='grow'></div>
                    <button class='bg-green-100 px-2 text-green-700' type='submit'>&#10004;</button>
                </div>
            {/if}
        </form>
    {/if}

</div>
