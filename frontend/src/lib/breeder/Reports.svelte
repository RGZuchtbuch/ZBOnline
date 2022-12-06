<script>
    import { onMount } from 'svelte';
    import { dec, perc } from '../../js/util.js';

    import api from '../../js/api.js';
    import {meta} from 'tinro';

    export let breederId;
    let breeder;
    let results = [];

    const route = meta();

    function handle( breederId ) {
        api.breeder.results.get( breederId ).then( data => {
            breeder = data.breeder;
            results = data.results;
        })
    }

    onMount( () => {
    });

    $: handle( breederId )

// promise={api.breeder.results.get(meta.params.breederId)
</script>



<div class='flex flex-col my-2'>
    {#if breeder && results}
        <h2 class='text-center' >Meldungen für Züchter {breeder.name}</h2>
        <div class='flex flex-row gap-x-1 text-xs bg-gray-200 px-2 pt-2'>
            <div class='w-32'>Meldung</div>
            <div class='w-0'></div>
            <div class='w-0'></div>
            <div class='w-80'>Standard</div>
            <div class='w-0'></div>
            <div class='w-36'>Legen</div>
            <div class='w-0'></div>
            <div class='w-0'></div>
            <div class='w-40'>Bruten</div>
            <div class='w-0'></div>
            <div class='w-0'></div>
            <div class='w-24'>Schauen</div>
            <div class='w-0'></div>
        </div>
        <div class='flex flex-row gap-x-1 text-xs bg-gray-200 px-2'>
            <div class='w-8'>Jahr</div>
            <div class='w-8'>Grp</div>
            <div class='w-16'>Stamm</div>
            <div class='w-48'>Rasse</div>
            <div class='w-32'>Farbe</div>
            <div class='w-12'>Hennen</div>
            <div class='w-10'>Eier/J</div>
            <div class='w-14'>Gewicht</div>
            <div class='w-12'>Eingelegt</div>
            <div class='w-14'>Befruchtet</div>
            <div class='w-14'>Geschüpft</div>
            <div class='w-10'>Tiere</div>
            <div class='w-14'>Bewertung</div>
            <a class='w-8' href={route.match+'/neu'}>+</a>
        </div>

        <div class='bg-gray-100 overflow-y-scroll border border-gray-300 scrollbar px-2'>
            {#each results as result}
                <a class='flex flex-row gap-x-1 text-xs' href={ route.match+'/'+result.reportId}>
                    <div class='w-8'>{result.year}</div>
                    <div class='w-8'>{result.group}</div>

                    <div class='w-16'>{result.reportName}</div>

                    <div class='w-48'>{result.breedName}</div>
                    <div class='w-32'>{result.colorName}</div>

                    <div class='w-12'>{dec(result.layDames)}</div>
                    <div class='w-10'>{dec(result.layEggs)}</div>
                    <div class='w-14'>{dec(result.layWeight)}</div>

                    <div class='w-12'>{dec(result.broodEggs)}</div>
                    <div class='w-14'>{perc( result.broodFertile, result.broodEggs )}</div>
                    <div class='w-14'>{perc( result.broodHatched, result.broodEggs )}</div>

                    <div class='w-10'>{dec(result.showCount)}</div>
                    <div class='w-14'>{dec(result.showScore)}</div>
                </a>
            {/each}
        </div>
    {/if}
</div>


<style>

</style>