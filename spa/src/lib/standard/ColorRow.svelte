<script>
    import { meta } from 'tinro';
    import { user } from '../../js/store.js';
    import { txt }  from '../../js/util.js';
    import api from "../../js/api.js";

    import Button from '../common/input/Button.svelte';
    import NumberInput from '../common/input/Number.svelte';
    import TextInput from '../common/input/Text.svelte';
    import TextArea from '../common/input/TextArea.svelte';

    export let breed;
    export let color = null;

    let details = null;
    let showDetails = false;
    let edit = false;
    let changed = false;

    let route = meta();

    function onDetails() {
        if( color ) {
            if( color.id === null ) {
                details = Object.assign( {}, color );
            } else {
                api.color.get(color.id).then(response => {
                    details = response.color;
                });
            }
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
        color.name = details.name;
        api.color.post( details ).then( response => {
            details.id = response.id;
            color.id = response.id;
            console.log( 'Response', response );
        })
        console.log( 'Submit color' );
    }

</script>

<div class='flex flex-col pl-8'>

    <div class='flex flex-row border-b border-gray-300 gap-x-1 my-1'>
        <div class='w-4'>⤷</div>
        <div class='grow cursor-pointer'>
            <a href={route.match+'/sparte/'+breed.sectionId+'/rasse/'+color.breedId+'/farbe/'+color.id}>
                {color.name}
            </a>
        </div>

        <div class='grow'></div>
        <div class='w-12 text-xs'>[{color.id}]</div>

    </div>

    {#if showDetails && details}
        <form class='ml-16 p-2 border border-gray-600' on:change={onChange} on:submit|preventDefault={onSubmit}>
            <div class='flex flex-row'>
                <div class='grow'><TextInput class='w-64' label={'Name der Rasse'} bind:value={details.name} disabled={! edit}/></div>
                {#if $user && $user.admin}
                    <div class='w-8 cursor-pointer' on:click={onEdit} title='Edit'>[ E ]</div>
                {/if}
            </div>
            <div class='flex flex-row'>
                <NumberInput class='w-24' label='AOC' bind:value={details.aoc} disabled={! edit}/>
            </div>

            {#if edit}
                <TextArea label='Edit Info in html' bind:value={details.info} disabled={!edit} />
            {/if}

            <div class='flex flex-col'>
                <span class='label'>Info</span>
                <div class=''>{@html txt(details.info)}</div>
            </div>

            {#if changed}
                <div class='flex flex-row'>
                    <div class='grow'></div>
                    <button class='bg-green-100 px-2 text-green-700' type='submit'>&#10004;</button>
                </div>
            {/if}
        </form>
    {/if}

</div>
