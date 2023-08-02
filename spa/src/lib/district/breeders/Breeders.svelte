<script>
    import {createEventDispatcher, onMount} from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../../js/api.js';
    import { user } from '../../../js/store.js'

    import BreederRow from "./BreederRow.svelte";

    export let district = null;

    let breeders = null;
    let clubs = null;

    let showInactive = false; // should ex members be included ?

    const route = meta();

    function onAddBreeder() { // event

        let breeder = {
            id: null,
            firstname: null,
            infix: null,
            lastname: null,
            email: null,
            districtId: district.id,
            districtName: district.name,
            clubId: null,
            clubName: null,
            start: Date.now(),
            end: null,
            active: true,
            info: null
        };
        breeders.unshift(breeder);
        breeders = breeders;
    }


    function loadBreeders( district ) {
        api.district.breeders.get( district.id ).then( response => {
           breeders = response.breeders;
           console.log( 'Breeders', breeders );
        });
    }


    onMount( () => {
    })


    $: loadBreeders( district );

    console.log( 'Check', $user.admin, $user.id, district.moderatorId );

</script>
{#if $user && ( $user.admin || $user.id === district.moderatorId ) }

    <h3 class='w-256 text-center'>
        {district.name} - Zuchtbuchmitglieder
    </h3>

    <div class='w-256 bg-gray-100 overflow-y-scroll border border-gray-400 rounded scrollbar'>

        <div class='flex flex-row border border-gray-400 rounded-t px-4 py-1 bg-header gap-x-1 font-bold'>
            <div class='w-4'>Id</div>
            <div class='w-56'>Name</div>
            <div class='w-36'>Ortsverein</div>
            <div class='w-64'>ZB Verband</div>
            <div class='w-24'>Seit</div>
            <div class='w-16'>Inaktive</div> <input class='cursor-pointer' type='checkbox' bind:checked={showInactive}>
            <div class='grow'></div>
            {#if $user && $user.moderator}
                <div class='cursor-pointer' title='Neues Mitglied' on:click={onAddBreeder}>[+]</div>
            {/if}
        </div>

        {#if breeders}
            {#each breeders as breeder}
                <BreederRow {breeder} {showInactive} />
            {/each}
        {/if}
    </div>
{:else}
    NOT AUTORIZED
{/if}



<style>

</style>