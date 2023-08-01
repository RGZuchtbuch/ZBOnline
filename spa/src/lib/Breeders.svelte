<script>
    import { createEventDispatcher } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import { user } from '../js/store.js'

    export let breeders = null;
    export let moderator = null;

    const route = meta();
    const dispatch = createEventDispatcher();

    function onAddMember( event ) {
        dispatch( 'addMember', )
    }


</script>


<div class='flex flex-row border border-gray-400 rounded-t px-8 py-2 bg-header gap-x-1 font-bold'>
    <div class='w-8'>Id</div>
    <div class='w-64'>Name</div>
    <div class='w-32'>Ortsverein</div>
    <div class='w-32'>Meldungen</div>
    <div class='grow'></div>
    {#if moderator}
        <div class='w-8' on:click={onAddMember} title='Neues Mitglied'>+</div>
    {/if}
</div>

<div class='grow bg-gray-100 overflow-y-scroll border border-t-0 border-gray-600 rounded-b px-8 scrollbar'>
    {#if breeders}
        {#each breeders as breeder}
            <div class='flex flex-row gap-x-1'>
                <a class='w-8 border' href={route.match+'/'+breeder.id}>{breeder.id}</a>
                <a class='w-64 border' href={route.match+'/'+breeder.id}>{breeder.name}</a>
                <a class='w-32 border' href={route.match+'/'+breeder.id}>{breeder.clubName}</a>
                <a class='w-32 border' href={route.match+'/'+breeder.id}></a>
            </div>
        {/each}
    {/if}
</div>


<style>

</style>