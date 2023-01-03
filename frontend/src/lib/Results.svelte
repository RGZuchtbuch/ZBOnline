<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import { dec, perc } from '../js/util.js';

    import api from '../js/api.js';

    import InputNumber from './input/Number.svelte';
    import InputText   from './input/Text.svelte';
    import Select from './input/Select.svelte';
    import BreedSelect from './select/Breed.svelte';

    export let promise;
    let breeder = null;
    let results = [];

    function handle( promise ) {
        if( promise ) {
            promise.then( (data) => {
                breeder = data.breeder;
                results = data.results;
            })
        }
    }

    onMount( () => {
    });

    $: handle( promise )
</script>



<div class='flex flex-col my-2'>
    {#if breeder && results}
        <div class='flex flex-row gap-x-2'>
            <span>Leistungen für {breeder.name}</span>
        </div>
        <div class='flex flex-row gap-x-1 text-xs'>
            <div class='w-8'>Jahr</div>
            <div class='w-8'>Grp</div>
            <div class='w-16'>Stamm</div>
            <div class='w-48'>Rasse</div>
            <div class='w-32'>Farbe</div>
            <div class='w-12'>Zuchten</div>
            <div class='w-12'>Hennen</div>
            <div class='w-10'>Eier/J</div>
            <div class='w-14'>Eiggewicht</div>
            <div class='w-12'>Eigelegt</div>
            <div class='w-14'>Befruchtet</div>
            <div class='w-14'>Geschüpft</div>
            <div class='w-10'>Tiere</div>
            <div class='w-14'>Bewertung</div>
        </div>
        {#each results as result}
            <div class='flex flex-row gap-x-1 text-xs'>
                <div class='w-8'>{result.year}</div>
                <div class='w-8'>{result.group}</div>

                <div class='w-16'>{result.reportName}</div>

                <div class='w-48'>{result.breedName}</div>
                <div class='w-32'>{result.colorName}</div>
                <div class='w-12'>1</div>

                <div class='w-12'>{dec(result.layDames)}</div>
                <div class='w-10'>{dec(result.layEggs)}</div>
                <div class='w-14'>{dec(result.layWeight)}</div>

                <div class='w-12'>{dec(result.broodEggs)}</div>
                <div class='w-14'>{perc( result.broodFertile, result.broodEggs )}</div>
                <div class='w-14'>{perc( result.broodHatched, result.broodEggs )}</div>

                <div class='w-10'>{dec(result.showCount)}</div>
                <div class='w-14'>{dec(result.showScore)}</div>
            </div>
        {/each}
    {/if}
</div>


<style>

</style>