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

            <div class='flex flex-row px-2 gap-x-4 text-center font-bold'>
                <div class='w-64'></div>
                <div class='w-32 border-b'>Zucht</div>
                <div class='w-48 border-b'>Legeleistung</div>
                <div class='w-60 border-b'>Brutleistung</div>
                <div class='w-32 border-b'>Schauleistung</div>
            </div>
            {#if section.id === 5}
                <div class='flex flex-row px-2 gap-x-4 text-center text-xs font-bold'>
                    <div class='w-64'>Rasse & Farbe</div>
                    <div class='flex w-32 gap-x-1'>
                        <div class='w-14'>Zuchten</div>
                        <div class='w-14'>Paare</div>
                    </div>
                    <div class='flex w-48 gap-x-1'>
                        <div class='w-14'></div>
                        <div class='w-14'></div>
                        <div class='w-14'></div>
                    </div>
                    <div class='flex w-60 gap-x-1'>
                        <div class='w-14'>Bruten</div>
                        <div class='w-14'></div>
                        <div class='w-14'>Küken</div>
                        <div class='w-14'>Kü/Pa</div>
                    </div>
                    <div class='flex w-32 gap-x-1'>
                        <div class='w-14'>Tiere</div>
                        <div class='w-14'>Punkte</div>
                    </div>
                </div>

            {:else}
                <div class='flex flex-row px-2 gap-x-4 text-center text-xs font-bold'>
                    <div class='w-64 text-left'>Rasse & Farbe</div>
                    <div class='flex w-32 gap-x-1'>
                        <div class='w-14'>Zuchten</div>
                        <div class='w-14'>Stämme</div>
                    </div>
                    <div class='flex w-48 gap-x-1'>
                        <div class='w-14'>Hennen</div>
                        <div class='w-14'>Eier/J</div>
                        <div class='w-14'>Gewicht</div>
                    </div>
                    <div class='flex w-60 gap-x-1'>
                        <div class='w-14'>Eier</div>
                        <div class='w-14'>Befr.</div>
                        <div class='w-14'>Küken</div>
                        <div class='w-14'></div>
                    </div>
                    <div class='flex w-32 gap-x-1'>
                        <div class='w-14'>Tiere</div>
                        <div class='w-14'>Punkte</div>
                    </div>
                </div>
            {/if}

            {#each section.breeds as breed, i}

                <div class='flex flex-row mt-1 px-2 gap-x-4 bg-gray-100'>
                    <div class='w-64'>{breed.name}</div>
                    {#if section.id === 5 && breed.result}
                        <div class='flex w-32 gap-x-1'>
                            <div class='cell'>{dec( breed.result.breeders )}</div>
                            <div class='cell'>{dec( breed.result.pairs )}</div>
                        </div>
                        <div class='flex w-48 gap-x-1'>
                            <div class='cell'></div>
                            <div class='cell'></div>
                            <div class='cell'></div>
                        </div>
                        <div class='flex w-60 gap-x-1'>
                            <div class='cell'>{dec( breed.result.broodEggs ? breed.result.broodEggs/2 : null )}</div>
                            <div class='cell'></div>
                            <div class='cell'>{breed.result.broodEggs && breed.result.broodHatched ? pct( breed.result.broodHatched, breed.result.broodEggs, 1 ) : '' }</div>
                            <div class='cell'>{dec( breed.result.broodEggs && breed.result.broodHatched ? breed.result.broodHatched/breed.result.pairs : null, 1 )}</div>
                        </div>
                        <div class='flex w-32 gap-x-1'>
                            <div class='cell'>{dec( breed.result.showCount )}</div>
                            <div class='cell'>{dec( breed.result.showScore, 1 )}</div>
                        </div>
                    {/if}
                </div>
                {#each breed.colors as color}
                    <div class='flex flex-row px-2 gap-x-4 print-no-break'>

                        <div class='w-64 pl-4'>&#10551; {color.name}</div>
                        {#if color.result}
                            <div class='flex w-32 gap-x-1'>
                                <div class='cell'>{dec( color.result.breeders )}</div>
                                <div class='cell'>{dec( color.result.pairs )}</div>
                            </div>
                            <div class='flex w-48 gap-x-1'>
                                <div class='cell'>{dec( color.result.layDames )}</div>
                                <div class='cell'>{dec( color.result.layEggs )}</div>
                                <div class='cell'>{dec( color.result.layWeight, 1 )}</div>
                            </div>
                            <div class='flex w-60 gap-x-1'>
                                <div class='cell'>{dec( color.result.broodEggs )}</div>
                                <div class='cell'>{pct( color.result.broodFertile, color.result.broodEggs )}</div>
                                <div class='cell'>{pct( color.result.broodHatched , color.result.broodEggs )}</div>
                                <div class='cell'></div>
                            </div>
                            <div class='flex w-32 gap-x-1'>
                                <div class='cell'>{dec( color.result.showCount )}</div>
                                <div class='cell'>{dec( color.result.showScore, 1 )}</div>
                            </div>
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
    .cell {
        @apply w-14 border-b border-gray-200 px-1 text-right;
    }
</style>