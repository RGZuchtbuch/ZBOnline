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


    export let club;

    let disabled = true;
    let changed = false;
    let members = null; // for selecting obmann

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
        api.district.post( club ).then( response => {
            club.id = response.id;
            changed = false;
        });
    }

</script>


{#if club}
    <Page>
        <div slot='title'> Verein {club.name ? club.name : '?'} </div>

        <div slot='header' class='flex flex-row'>
            <div class='grow'>Vereinsdaten</div>
            {#if $user.admin || $user.moderator}
                <div class='w-6 h-6 border-2 border-alert rounded bg-white align-middle text-center text-red-600 cursor-pointer' class:disabled on:click={onToggleEdit} title={ disabled ? 'Daten ändern' : 'Daten nicht ändern' }>&#9998;</div>
            {/if}
        </div>

        <form slot='body' class='flex flex-col' on:input={onChange}>
            <fieldset {disabled}>

                <div class='flex gap-2'>
                    <Text class='w-64' bind:value={club.name} label='Name' required/>
                </div>
                <Text class='w-128' bind:value={club.fullname} label='Name voll' required/>
                <Text class='w-24' bind:value={club.short} label='Name abk.' required/>

                {#if ! disabled && changed}
                    {#if club.name}
                        <div class='bg-alert text-center font-bold text-white cursor-pointer' on:click={onSubmit}>Speichern</div>
                    {:else}
                        <div class='bg-alert text-center font-bold text-white cursor-pointer' on:click={onSubmit}>Löschen !</div>
                    {/if}
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