<script>
    import { createEventDispatcher } from 'svelte';
    import {meta} from "tinro";
    import api from '../../js/api.js';
    import {user} from '../../js/store.js';
    import {txt} from '../../js/util.js';
    import Number from '../common/input/Number.svelte';
    import Select from '../common/input/Select.svelte';
    import Text from '../common/input/Text.svelte';
    import Page from "../common/Page.svelte";
    import Districts from '../districts/DistrictsList.svelte';

    export let districtId;

    let district;
    let disabled = true;
    let changed = false;
    let members = null; // for selecting obmann
    let childDistricts = null;

    const route    = meta();
    const dispatch = createEventDispatcher();

    function onToggleEdit() {
        disabled = ! disabled;
    }

    function onChange( event ) {
        changed = true;
    }

    function onSubmit() {
        disabled = true;
        if( district.id > 0 ) {
            api.district.put( district.id, district ).then(response => {
                changed = false;
            });

        } else {
            api.district.post(district).then(response => {
                district.id = response.id;
                changed = false;
            });
        }
    }

    function loadDistrict( id ) {
        api.district.get( id ).then( response => {
           district = response.district;
        });
    }

    function loadMembers( id ) {
        api.district.breeders.get( id ).then(response => {
            members = response.breeders;
        })
    }

    $: loadDistrict( districtId );
    $: loadMembers( districtId );
</script>


{#if district}
    <Page>
        <div slot='title'> Verband {district.name} </div>

        <div slot='header' class='flex flex-row'>
            <div class='grow'>Verbandsdaten</div>
            {#if $user.admin}
                <div class='w-6 h-6 border-2 border-alert rounded bg-white align-middle text-center text-red-600 cursor-pointer' class:disabled on:click={onToggleEdit} title={ disabled ? 'Daten ändern' : 'Daten nicht ändern' }>&#9998;</div>
            {/if}
        </div>

        <form slot='body' class='flex flex-col' on:input={onChange}>
            <fieldset {disabled}>

                <div class='flex gap-2'>
                    <Text class='w-64' bind:value={district.name} label='Name' required/>
                </div>
                <Text class='w-128' bind:value={district.fullname} label='Name voll' required/>
                <Text class='w-24' bind:value={district.short} label='Name abk.' required/>

                <div class='flex gap-x-2'>
                    <Number class='w-32' bind:value={district.latitude} label='Breitegrad N' min={MINLATITUDE} max={MAXLATITUDE} required />
                    <Number class='w-32' bind:value={district.longitude}  label='Längegrad O' min={MINLONGITUDE} max={MAXLONGITUDE} required />
                </div>

                <Select class='w-128' bind:value={district.moderatorId} label='Zuchtbuch Obmann' >
                    {#if members}
                        <option value={null}></option>
                        {#each members as member}
                            <option value={member.id} selected={district.moderatorId == member.id ? 'selected' : '' }>
                                {txt(member.lastname)}, {txt(member.firstname)} {txt(member.infix)}
                            </option>
                        {/each}
                    {/if}
                </Select>

                {#if ! disabled && changed}
                    <div class='bg-alert text-center font-bold text-white cursor-pointer' on:click={onSubmit}>Speichern</div>
                {/if}
            </fieldset>
        </form>
    </Page>
{/if}


<style>
    .disabled {
        @apply text-green-600;
    }
    select {
        background: green;
    }
</style>