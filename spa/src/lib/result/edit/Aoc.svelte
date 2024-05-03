<script>
    import { slide } from 'svelte/transition';

    import api          from '../../../js/api.js';
    import Result  from './Result.svelte';

    export let districtId = null;
    export let sectionId = null;
    export let result = null;
    export let year = null;
    export let group = null;
    export let title = null;

    //export let open = true;
    //let results = [];
    //let hasResults = 0;

    function onOpen() {
        if( open ) {
            open = false;
        } else {
            if( results.length === 0 ) { // only if not already fetched
                api.district.results.breed.get( districtId, sectionId, breed.id, year, group ).then( response => {
                    results = response.results;
                })
            } else {
                results = results;
            }
            open = true;
        }
        console.log( 'Open', open );

    }
/*
    function recount( b) {
        if( results.length > 0 ) {
            let counter = 0;
            for (let result of results) {
                if (result.breeders && result.breeders > 0) {
                    counter++;
                }
            }
            breed.results = counter;
            breed = breed;
        }
    }

 */

    //$: recount( breed );
    //$: hasResults = breed.results;
    console.log( 'Breed/Result', result )
</script>



<div class='flex flex-row px-2 gap-x-1'>
    <div class='w-80 cursor-pointer whitespace-nowrap'  {title}>
        {result.breedName}
    </div>

    <div class='w-4'></div>

    {#if result.sectionId === PIGEONS }
        <div class='flex flex-row gap-x-1 text-xs text-center'>
            <div class='w-14'>Zuchten</div> <div class='w-14'>Paare</div>
            <div class='w-2'></div>
            <div class='w-14'></div> <div class='w-14'></div> <!-- div class='w-14'></div -->
            <div class='w-2'></div>
            <div class='w-14'></div> <div class='w-14'>Küken</div> <div class='w-14'></div>
            <div class='w-2'></div>
            <div class='w-14'>Tiere</div> <div class='w-14 whitespace-nowrap'>Note</div>
        </div>
    {:else}
        <div class='flex flex-row gap-x-1 text-xs text-center'>
            <div class='w-14'>Zuchten</div> <div class='w-14'></div>
            <div class='w-2'></div>
            <!-- div class='w-14'>Hennen</div --> <div class='w-14'>Eier/J</div> <div class='w-14 whitespace-nowrap'>Gewicht</div>
            <div class='w-2'></div>
            <div class='w-14'>Eingelegt</div> <div class='w-14'>Befruchtet</div> <div class='w-14'>Geschlüpft</div>
            <div class='w-2'></div>
            <div class='w-14'>Tiere</div> <div class='w-14 whitespace-nowrap'>Note</div>
        </div>
    {/if}
    <div class='w-4'></div>

</div>

<div>
        <Result sectionId={result.sectionId} {result}/>
</div>

<style>

</style>