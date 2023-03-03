<script>
    import { onMount } from 'svelte';
    import { dec, pct } from '../js/util.js';

    import {meta, router} from 'tinro';

    export let results = [];

    const route = meta();

    onMount( () => {
    });

</script>

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
                        <div class='w-56'>{color.name}</div>
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
        {#if results.sections.length == 0 }
            <h2 class='p-2 bg-header text-center text-xl'>Leider keine Daten für dieses Jahr</h2>
        {/if}
    {/if}
</div>




<style>

</style>