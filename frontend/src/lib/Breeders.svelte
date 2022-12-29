<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';

    export let breeders;

    const route = meta();

    onMount( () => {
    })

</script>


<div class='flex flex-row border border-gray-600 rounded-t bg-gray-300 px-4 gap-x-1 font-bold'>
    <div class='w-8'>Id</div>
    <div class='w-64'>Name</div>
    <div class='w-32'>Ortverein</div>
    <div class='w-32'>Meldungen</div>
    <a class='w-8' href={route.match+'/neu'} >+</a>
</div>

<div class='grow bg-gray-100 overflow-y-scroll border border-t-0 border-gray-600 rounded-b px-2 scrollbar'>
    {#if breeders}
        {#each breeders as breeder}
            <div class='flex flex-row gap-x-1'>
                <a class='w-8 border' href={route.match+'/'+breeder.id}>{breeder.id}</a>
                <a class='w-64 border' href={route.match+'/'+breeder.id}>{breeder.name}</a>
                <a class='w-32 border' href={route.match+'/'+breeder.id}></a>
                <a class='w-32 border' href={route.match+'/'+breeder.id}></a>
            </div>
        {/each}
    {/if}
</div>


<style>

</style>