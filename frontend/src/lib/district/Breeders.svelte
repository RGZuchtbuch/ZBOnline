<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';


    export let districtId;

    const route = meta();
    let district;
    let breeders = [];

    function handle( districtId ) {
        api.district.get( districtId ).then( data => {
            district = data.district;
            console.log( district );
        })
        api.district.breeders.get( districtId ).then( data => {
            breeders = data.breeders;
        });
    }

    onMount( () => {
    })

    $: handle( districtId );
    //promise={api.district.breeders.get(meta.params.districtId) } legend='Züchter'

</script>

<div class='grow flex flex-col bg-gray-100 border border-black rounded p-4'>
    {#if district}
        <h2 class='text-center'>Züchter für Verband {district.name}</h2>
    {/if}

    <div class='flex flex-row bg-gray-300 gap-x-1'>
        <div class='w-8'>Id</div>
        <div class='w-64'>Name</div>
        <div class='w-32'>Ortverein</div>
        <div class='w-32'>Meldungen</div>
        <a class='w-8' href={route.match+'/neu'} >+</a>
    </div>
    <div class='grow bg-gray-100 overflow-y-scroll border border-gray-300 scrollbar'>
        {#if breeders}
            {#each breeders as breeder}
                <div class='flex flex-row gap-x-1'>
                    <a class='w-8 border' href={route.match+'/'+breeder.id}>{breeder.id}</a>
                    <a class='w-64 border' href={route.match+'/'+breeder.id}>{breeder.name}</a>
                </div>
            {/each}
        {/if}
    </div>
</div>

<style>

</style>