<script>
    import { createEventDispatcher } from 'svelte';
    import {meta} from "tinro";
    import api from '../../js/api.js';
    import {user} from '../../js/store.js';
    import {txt} from '../../js/util.js';
    import Number from '../common/input/Number.svelte';
    import Select from '../common/input/Select.svelte';
    import Text from '../common/input/Text.svelte';

    export let districtId;

    let district;
    let disabled = true;
    let changed = false;
    let moderatorOptions = null;

    const route    = meta();
    const dispatch = createEventDispatcher();

    function onToggleEdit() {
        console.log( 'edit' );
        disabled = ! disabled;
        if( ! disabled ) {
            api.district.breeders.get(district.id).then(response => {
                moderatorOptions = response.breeders;
            })
        }
    }

    function onChange( event ) {
        changed = true;
    }

    function onSubmit() {
        console.log( 'Submit district', district );
        disabled = true;
        api.district.post( district ).then( response => {
            district.id = response.id;
            changed = false;
        });
    }

    function loadDistrict( id ) {
        api.district.get( id ).then( response => {
           district = response.district;
        });
    }


    $: loadDistrict( districtId );
</script>

<div class='flex flex-col'>

    <h2 class='w-256 border border-gray-400 rounded-t p-2 bg-header text-center text-xl print'>Verbandsdaten </h2>
    {#if district }
        <form class='flex flex-col bg-gray-200 border border-gray-400 rounded-b p-4' on:input={onChange}>
            <fieldset {disabled}>
                <div class='flex gap-2'>
                    <Text class='w-64' bind:value={district.name} label='Name' required/>
                    <div class='grow'></div>
                    {#if $user.admin && !changed}
                        <div class='border-2 border-gray-400 rounded w-7 h-7 align-middle text-center text-red-600 cursor-pointer' class:disabled on:click={onToggleEdit}>&#9998;</div>
                    {/if}
                </div>
                <Text class='w-128' bind:value={district.fullname} label='Name voll' required/>
                <Text class='w-24' bind:value={district.short} label='Name abk.' required/>
                <div class='flex gap-x-2'>
                    <Number class='w-32' bind:value={district.latitude} label='Breitegrad N]' min={MINLATITUDE} max={MAXLATITUDE} required />
                    <Number class='w-32' bind:value={district.longitude}  label='Längegrad O' min={MINLONGITUDE} max={MAXLONGITUDE} required />
                </div>
                {#if disabled }
                    <Text class='w-128' value={ txt( district.moderator.lastname)+', '+txt(district.moderator.firstname)+' '+txt(district.moderator.infix) } label='Obmann' />
                {:else}
                    <Select class='w-128' bind:value={district.moderatorId} label='Obmann' >
                        {#if moderatorOptions}
                            <option value={null}></option>
                            {#each moderatorOptions as option}
                                <option value={option.id} selected={option.id === district.moderator.id}>
                                    {txt(option.lastname)}, {txt(option.firstname)} {txt(option.infix)}
                                </option>
                            {/each}
                        {/if}
                    </Select>
                {/if}

                {#if ! disabled && changed}
                    <div class='bg-alert text-center font-bold text-white cursor-pointer' on:click={onSubmit}>Speichern</div>
                {/if}
            </fieldset>
        </form>
    {/if}
</div>

<style>
    .disabled {
        @apply text-green-600;
    }
    select {
        background: green;
    }
</style>