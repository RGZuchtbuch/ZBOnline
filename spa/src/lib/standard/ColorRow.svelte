<script>
    import { user } from '../../js/store.js';
    import api from "../../js/api.js";

    import Button from '../input/Button.svelte';
    import NumberInput from '../input/Number.svelte';
    import TextInput from '../input/Text.svelte';

    export let color = null;

    let details = false;
    let edit = false;
    let changed = false;

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
        api.color.post( color ).then( response => {
            color.id = response.id;
            console.log( 'Response', response );
        })
        console.log( 'Submit color' );
    }

</script>

<div class='flex flex-col pl-8'>

    <div class='flex flex-row border-b border-gray-300 px-4 gap-x-1'>
        <div class='w-12 text-xs'># {color.id}</div>

        <div class='grow cursor-pointer'>
            {color.name}
        </div>
        <div class='w-8 cursor-pointer' on:click={onDetails} title='Details'>[ D ]</div>
    </div>

    {#if details}
        <form on:change={onChange} on:submit|preventDefault={onSubmit}>
            <div class='flex flex-row pl-16'>
                <div class='grow'><TextInput class='w-64' label={'Name der Rasse'} bind:value={color.name} disabled={! edit}/></div>
                {#if $user && $user.admin}
                    <div class='w-8 cursor-pointer' on:click={onEdit} title='Edit'>[ E ]</div>
                {/if}
            </div>
            <div class='flex flex-row pl-16 gap-x-2'>
                <NumberInput class='w-24' label='AOC' bind:value={color.aoc} disabled={! edit}/>
            </div>
            <div class='flex flex-row pl-16 gap-x-2 '>
                <TextInput class='w-128' label='Info' bind:value={color.info} disabled={! edit}/>
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
