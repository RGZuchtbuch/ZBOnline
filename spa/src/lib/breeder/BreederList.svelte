<script>
    import { createEventDispatcher, onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { txt } from '../../js/util.js';
    import { user } from '../../js/store.js'
    import BreederRow from './BreederListRow.svelte';
    import Date from "../common/input/Date.svelte";


    export let breeders = null;

    let showAll = false; // should ex members be included ?

    let districts = null;

    const dispatch = createEventDispatcher();
    const route = meta();

    function addBreeder() {
        dispatch( "addBreeder" );
    }

//    $: onChange( breeders );

</script>


<div class='flex flex-row border border-gray-400 rounded-t px-4 py-1 bg-header gap-x-1 font-bold'>
    <div class='w-4'>Id</div>
    <div class='w-56'>Name</div>
    <div class='w-36'>Ortsverein</div>
    <div class='w-64'>ZB Verband</div>
    <div class='w-24'>Seit</div>
    <div class='w-16'>Inaktive</div> <input class='cursor-pointer' type='checkbox' bind:checked={showAll}>
    <div class='grow'></div>
    {#if $user && $user.moderator}
        <div class='cursor-pointer' title='Neues Mitglied' on:click={addBreeder}>[+]</div>
    {/if}
</div>

{#if breeders}
    {#each breeders as breeder}
        <BreederRow {breeder} {showAll} />
    {/each}
{/if}



<style>

</style>