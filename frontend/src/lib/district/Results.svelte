<script>
    import { onMount } from 'svelte';
    import { dec, perc } from '../../js/util.js';

    import api from '../../js/api.js';
    import {meta, router} from 'tinro';

    import Results from '../Results.svelte';
    import Select from '../input/Select.svelte';

    export let districtId;


    let district;

    let results = [];

    let lineCount = 0; // for page braks

    const route = meta();
    let year = route.query.year || new Date().getFullYear();

    const years = [];
    for( let i=year; i>=2000; i-- ) {
        years.push(i);
    }

    function handle( districtId ) {
        api.district.results.get( districtId, year ).then( data => {
            district = data.district;
            results = data.results;
            console.log( 'DistrictResults', results );
        })
    }

    function selectYear( year ) {
        router.goto( route.match+'?year='+year );
    }

    function needsPageBreak() {
        console.log( 'PB', lineCount );
        let needsPageBreak = false;
        if( lineCount % MAXLINESPERPAGE === 0 ) {
            lineCount = 0;
            needsPageBreak = true;
        }
        lineCount ++;
        return needsPageBreak;
    }

    onMount( () => {
    });

    $: handle( districtId, year )
    $: selectYear( year );

    console.log( 'Year', year );

</script>


{#if district}
    <h2 class='text-center' >Leistungen für Verband {district.name}</h2>
    <div class='flex justify-center'>
        <Select class='w-24' label='Jahr' bind:value={year}>
            {#each years as year}
                <option value={year} >{year}</option>
            {/each}
        </Select>
    </div>

    <div class='bg-white overflow-y-scroll border border-gray-600 border-t-gray-400 rounded-b scrollbar print'>
        {#if results}
            {#each results.sections as section}
                <h2 class='p-2 bg-header text-center text-xl'>Sparte {section.name}</h2>

                {#if section.id === 5}
                    <div class='flex flex-row px-2 gap-x-1 font-bold'>
                        <div class='w-64'></div>
                        <div class='w-16'></div>
                        <div class='w-16'></div>
                        <div class='w-48'></div><div></div><div></div>
                        <div class='w-48'>Brutleistung</div><div></div><div></div>
                        <div class='w-16'>Schauleistung</div>
                    </div>

                    <div class='flex flex-row px-2 gap-x-1 font-bold'>
                        <div class='w-64'>Rasse & Farbe</div>
                        <div class='w-16'>Zuchten</div>
                        <div class='w-16'>Stämme</div>
                        <div class='w-16'></div>
                        <div class='w-16'></div>
                        <div class='w-16'></div>
                        <div class='w-16'>Soll Eier</div>
                        <div class='w-16'></div>
                        <div class='w-16'>Küken</div>
                        <div class='w-16'>Tiere</div>
                        <div class='w-16'>Bewertung</div>
                    </div>
                {:else}
                    <div class='flex flex-row px-2 gap-x-1 text font-bold'>
                        <div class='w-64'></div>
                        <div class='w-16'></div>
                        <div class='w-16'></div>
                        <div class='w-48'>Legeleistung</div><div></div><div></div>
                        <div class='w-48'>Brutleistung</div><div></div><div></div>
                        <div class='w-16'>Schauleistung</div>
                    </div>

                    <div class='flex flex-row px-2 gap-x-1 font-bold'>
                        <div class='w-64'>Rasse & Farbe</div>
                        <div class='w-16'>Zuchten</div>
                        <div class='w-16'>Stämme</div>
                        <div class='w-16'>Hennen</div>
                        <div class='w-16'>Eier/J</div>
                        <div class='w-16'>Gewicht</div>
                        <div class='w-16'>Eier</div>
                        <div class='w-16'>Befr.</div>
                        <div class='w-16'>Küken</div>
                        <div class='w-16'>Tiere</div>
                        <div class='w-16'>Bewertung</div>
                    </div>
                {/if}

                {#each section.breeds as breed, i}

                    <div class='flex flex-row px-2 gap-x-1 '>
                        <div class='w-64'>{breed.name}</div>
                        {#if breed.result}
                            <div class='w-16'>{dec( breed.result.breeders )}</div>
                            <div class='w-16'>{dec( breed.result.pairs )}</div>
                            <div class='w-16'></div>
                            <div class='w-16'></div>
                            <div class='w-16'></div>

                            <div class='w-16'>{dec( breed.result.broodEggs )}</div>
                            <div class='w-16'></div>
                            <div class='w-16'>{dec( breed.result.broodHatched )}</div>

                            <div class='w-16'>{dec( breed.result.showCount )}</div>
                            <div class='w-16'>{dec( breed.result.showScore, 1 )}</div>
                        {/if}
                    </div>
                    {#each breed.colors as color}
                        <div class='flex flex-row px-2 gap-x-1 print-no-break'>
                            <div class='w-8 pl-4'>&#10551; </div>
                            <div class='w-56'>Farbe {color.name}</div>
                            {#if color.result}
                                <div class='w-16'>{dec( color.result.breeders )}</div>
                                <div class='w-16'>{dec( color.result.pairs )}</div>
                                <div class='w-16'>{dec( color.result.layDames )}</div>
                                <div class='w-16'>{dec( color.result.layEggs )}</div>
                                <div class='w-16'>{dec( color.result.layWeight, 1 )}</div>

                                <div class='w-16'>{dec( color.result.broodEggs )}</div>
                                <div class='w-16'>{dec( color.result.broodFertile )}</div>
                                <div class='w-16'>{dec( color.result.broodHatched )}</div>

                                <div class='w-16'>{dec( color.result.showCount )}</div>
                                <div class='w-16'>{dec( color.result.showScore, 1 )}</div>
                            {/if}

                        </div>
                    {/each}
                {/each}
                <div class='print-break'></div>
            {/each}
        {/if}
    </div>

{/if}



<style>

</style>