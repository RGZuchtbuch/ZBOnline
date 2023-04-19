<script>
    import { onMount } from 'svelte';
    import { dec, pct } from '../js/util.js';

    import {meta, router} from 'tinro';

    export let results = [];

    const route = meta();

    onMount( () => {
    });

    function addTo( total, result ) {
        result.broods = result.broodEggs ? result.broodEggs / 2 : null;
        result.broodResult = result.pairs && result.broodHatched ? result.broodHatched / result.pairs : null;

        total.breeders += result.breeders;
        total.pairs += result.pairs;
        total.layDames += result.layDames;
        if( result.layEggs ) {
            total.layers += result.breeders;
            total.layEggs += result.breeders * result.layEggs; // needs div by total breeders
        }
        if( result.layWeight ) {
            total.layWeighters += result.breeders;
            total.layWeight += result.breeders * result.layWeight;
        }

        total.broodEggs += result.broodEggs;
        total.broodFertile += result.broodFertile;
        total.broodHatched += result.broodHatched;
        if( result.pairs && result.broodHatched ) {
            total.brooders += result.breeders;
            total.broodResult += result.breeders * result.broodHatched / result.pairs; // sum chicks per pair per result
        }
        total.showCount += result.showCount;
        if( result.showScore ) {
            total.showers += result.breeders;
            total.showScore += result.breeders * result.showScore;
        }
    }

    function calcTotals( results ) {
        if( results ) {
            for( const section of results.sections ) {
                let total = { breeders:0, pairs:0, layDames:0, layers:0, layEggs:0, layWeighters:0, layWeight:0, brooders:0, broodEggs:0, broodFertile:0, broodHatched:0, broodResult:0, showers:0, showCount:0, showScore:0 };
                for( const subsection of section.subsections ) {
                    for( const breed of subsection.breeds ) {
                        if( breed.result ) {
                            addTo( total, breed.result);
                        }
                        for( const color of breed.colors ) {
                            if( color.result ) {
                                addTo( total, color.result);
                            }
                        }
                    }
                }
                total.layEggs = total.layers ? total.layEggs / total.layers : null;
                total.layWeight = total.layWeighters ? total.layWeight / total.layWeighters : null;
                total.broodResult = total.brooders ? total.broodResult / total.brooders : null; // avg of ch/pair per breeder.
                total.showScore = total.showers ? total.showScore / total.showers : null;
                section.total = total;
            }
        }
    }

    $: calcTotals( results );

</script>


<div class='w-256'>
    {#if results}
        {#each results.sections as section}
            <h2 class='p-2 bg-header text-center text-xl'>Sparte {section.name}</h2>

            <div class='flex flex-row px-2 gap-x-4 text-center font-bold'>
                <div class='w-56'></div>
                <div class='w-28 border-b'>Zucht</div>
                {#if section.id === 5}
                    <div class='w-40 border-b'> x </div>
                {:else}
                    <div class='w-40 border-b'>Legeleistung</div>
                {/if}
                <div class='w-40 border-b'>Brutleistung</div>
                <div class='w-28 border-b'>Schauleistung</div>
            </div>

            {#each section.subsections as subsection}
                <div class='flex flex-row bg-gray-300 px-2 gap-x-4 font-bold text-xl text-left'>
                    {subsection.name}
                </div>
                <div class='flex flex-row bg-gray-300 px-2 gap-x-4 text-center text-xs'>
                    <div class='w-56 text-left'>Rasse & Farbe</div>
                    <div class='flex w-28 gap-x-1'>
                        <div class='th'>Zuchten</div>
                        <div class='th'>Paare</div>
                    </div>
                    {#if section.id === 5}
                        <div class='flex w-40 gap-x-1'>| <div class='td'></div> <div class='td'></div> <div class='td'></div> </div>
                    {:else}
                        <div class='flex w-40 gap-x-1'>| <div class='td'>Hennen</div> <div class='td'>Eier/J</div> <div class='td'>Gewicht</div> </div>
                    {/if}

                    {#if section.id === 5}
                        <div class='flex w-40 gap-x-1'>| <div class='td'>Bruten</div> <div class='td'>Küken</div> <div class='td'>Kü/Pa</div> </div>
                    {:else}
                        <div class='flex w-40 gap-x-1'>| <div class='td'>Eier</div> <div class='td'>Befr.</div> <div class='td'>Küken</div> </div>
                    {/if}

                    <div class='flex w-26 gap-x-1'>| <div class='td'>Tiere</div> <div class='td'>Punkte</div> </div>
                </div>

                {#each subsection.breeds as breed, i}
                    <div class='flex flex-row mt-1 px-2 gap-x-4 bg-gray-100'>
                        <div class='w-56'>{breed.name}</div>
                        {#if section.id === 5 && breed.result}
                            <div class='flex w-28 gap-x-1'> <div class='td'>{dec( breed.result.breeders )}</div> <div class='td'>{dec( breed.result.pairs )}</div> </div>
                            <div class='flex w-40 gap-x-1'> <div class='td'></div> <div class='w-14'></div> <div class='td'></div> </div>
                            <div class='flex w-40 gap-x-1'> <div class='td'>{dec( breed.result.broods )}</div>  <div class='td'>{dec( breed.result.broodHatched ) }</div>  <div class='td'>{dec( breed.result.broodResult, 1 )}</div> </div>
                            <div class='flex w-28 gap-x-1'> <div class='td'>{dec( breed.result.showCount )}</div> <div class='td'>{dec( breed.result.showScore, 1 )}</div> </div>
                        {/if}
                    </div>
                    {#each breed.colors as color}
                        {#if section.id !== 5 && color.result}
                            <div class='flex flex-row px-2 gap-x-4 print-no-break text-sm'>
                                <div class='w-56 pl-4'>&#10551; {color.name}</div>
                                <div class='flex w-28 gap-x-1'> <div class='td'>{dec( color.result.breeders )}</div> <div class='td'>{dec( color.result.pairs )}</div> </div>
                                <div class='flex w-40 gap-x-1'> <div class='td'>{dec( color.result.layDames )}</div> <div class='td'>{dec( color.result.layEggs )}</div> <div class='td'>{dec( color.result.layWeight, 1 )}</div> </div>
                                <div class='flex w-40 gap-x-1'> <div class='td'>{dec( color.result.broodEggs )}</div> <div class='td'>{pct( color.result.broodFertile, color.result.broodEggs )}</div> <div class='td'>{pct( color.result.broodHatched , color.result.broodEggs )}</div> </div>
                                <div class='flex w-28 gap-x-1'> <div class='td'>{dec( color.result.showCount )}</div> <div class='td'>{dec( color.result.showScore, 1 )}</div> </div>
                            </div>
                        {/if}
                    {/each}
                {/each}
            {/each}

            <div class='flex flex-row bg-gray-300  px-2 gap-x-4 font-bold text-sm italic'>
                <div class='w-56'>Gesamt {section.name}</div>
                {#if section.id === 5 }
                    <div class='flex w-28 gap-x-1'>
                        <div class='td'>{dec( section.total.breeders )}</div>
                        <div class='td'>{dec( section.total.pairs )}</div>
                    </div>
                    <div class='flex w-40 gap-x-1'>
                        <div class='td'></div>
                        <div class='td'></div>
                        <div class='td'></div>
                    </div>
                    <div class='flex w-40 gap-x-1'>
                        <div class='td'>{dec( section.total.broodEggs ? section.total.broodEggs/2 : null )}</div>
                        <div class='td'>{dec( section.total.broodHatched ) }</div>
                        <div class='td'>{dec( section.total.broodResult, 1 )}</div>
                    </div>
                    <div class='flex w-28 gap-x-1'>
                        <div class='td'>{dec( section.total.showCount )}</div>
                        <div class='td'>{dec( section.total.showScore, 1 )}</div>
                    </div>
                {:else}
                    <div class='flex w-28 gap-x-1'>
                        <div class='td'>{dec( section.total.breeders )}</div>
                        <div class='td'>{dec( section.total.pairs )}</div>
                    </div>
                    <div class='flex w-40 gap-x-1'>
                        <div class='td'>{dec( section.total.layDames )}</div>
                        <div class='td'>{dec( section.total.layEggs )}</div>
                        <div class='td'>{dec( section.total.layWeight, 1 )}</div>
                    </div>
                    <div class='flex w-40 gap-x-1'>
                        <div class='td'>{dec( section.total.broodEggs )}</div>
                        <div class='td'>{pct( section.total.broodFertile, section.total.broodEggs )}</div>
                        <div class='td'>{pct( section.total.broodHatched , section.total.broodEggs )}</div>
                    </div>
                    <div class='flex w-28 gap-x-1'>
                        <div class='td'>{dec( section.total.showCount )}</div>
                        <div class='td'>{dec( section.total.showScore, 1 )}</div>
                    </div>
                {/if}
            </div>
            <div class='print-break h-4'></div>
        {/each}
        {#if results.sections.length == 0 }
            <h2 class='p-2 bg-header text-center text-xl'>Leider keine Daten für dieses Jahr</h2>
        {/if}
    {/if}
</div>



<style>
    .th {
        @apply w-12 border-b border-gray-200 px-1 text-center;
    }
    .td {
        @apply w-12 border-b border-gray-200 px-1 text-right;
    }
</style>