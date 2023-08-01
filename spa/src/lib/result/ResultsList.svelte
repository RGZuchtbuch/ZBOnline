<script>
    import { onMount } from 'svelte';
    import { dec, pct } from '../../js/util.js';

    import {meta, router} from 'tinro';

    import Comment from '../common/Comment.svelte';

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


<div class='print'>
    {#if results}
        {#each results.sections as section}

            <Comment>TOP HEADERS</Comment>

            <h2 class='p-2 bg-header text-center text-white text-xl'>Sparte {section.name}</h2>
            <div class='flex flex-row px-2 gap-x-1 font-bold'>
                <div class='w-56 text-center'>Gruppe / Rasse / Farbe</div>
                <div class='grow flex flex-row justify-evenly'>
                    <div class='w-28 border-b text-center'>Zucht</div>
                    <div class='w-40 border-b text-center text-center'> {#if section.id === 5}-{:else}Legeleistung{/if} </div>
                    <div class='w-40 border-b text-center'>Brutleistung</div>
                    <div class='w-28 border-b text-center'>Schauleistung</div>
                </div>
            </div>

            {#each section.subsections as subsection}
                <div class='flex flex-row bg-gray-300 mt-4 px-2 gap-x-4 font-bold text-xl text-left'>
                    {subsection.name}
                </div>

                <Comment>SUB HEADERS</Comment>
                <div class='flex flex-row bg-gray-300 px-2 gap-x-1 text-xs'>
                    <div class='w-56 text-left'>Rasse & Farbe</div>
                    <div class='grow flex flex-row justify-evenly'>
                        <div class='flex w-28 justify-evenly'>
                            <div class='td'>Zuchten</div><div class='td'>Paare</div>
                        </div>
                        <div class='flex w-40 justify-evenly'>
                            {#if section.id === 5}
                                <div class='td'></div> <div class='td'></div> <div class='td'></div>
                            {:else}
                                <div class='td'>Hennen</div> <div class='td'>Eier/J</div> <div class='td'>Gewicht</div>
                            {/if}
                        </div>
                        <div class='flex w-40 justify-evenly'>
                            {#if section.id === 5}
                                <div class='td'></div> <div class='td'>Küken</div> <div class='td'>Kü/Pa</div>
                            {:else}
                                <div class='td'>Eier</div> <div class='td'>Befr.</div> <div class='td'>Küken</div>
                            {/if}
                        </div>
                        <div class='flex w-28 justify-evenly'>
                            <div class='td'>Tiere</div> <div class='td'>Punkte</div>
                        </div>
                    </div>
                </div>

                <Comment>BREEDS, RSEULTS AND COLOR RESULTS</Comment>
                {#each subsection.breeds as breed, i}
                    <div class='flex flex-row mt-1 px-2 gap-x-1 bg-gray-100'>
                        <div class='w-56 text-left text-base font-semibold'>{breed.name}</div>
                        {#if section.id === 5 && breed.result}
                            <div class='grow flex justify-evenly text-sm'>
                                <div class='flex w-28 justify-evenly'>
                                    <div class='td' title='Zahl der Zuchten / Züchter'>{dec( breed.result.breeders )}</div>
                                    <div class='td' title='Zahl der Paare'>{dec( breed.result.pairs )}</div>
                                </div>
                                <div class='flex w-40 justify-evenly'>
                                    <div class='td'></div>
                                    <div class='td'></div>
                                    <div class='td'></div>
                                </div>
                                <div class='flex w-40 justify-evenly'>
                                    <div class='td'></div>
                                    <div class='td' title='Geschlüpfte Küken'>{dec( breed.result.broodHatched ) }</div>
                                    <div class='td' title='Zahl der Küken pro Paar'>{dec( breed.result.broodResult, 1 )}</div>
                                </div>
                                <div class='flex w-28 justify-evenly'>
                                    <div class='td'>{dec( breed.result.showCount )}</div>
                                    <div class='td'>{dec( breed.result.showScore, 1 )}</div>
                                </div>
                            </div>
                        {:else}
                            <div class='grow'></div>
                        {/if}
                    </div>
                    {#each breed.colors as color}
                        {#if section.id !== 5 && color.result}
                            <div class='flex flex-row px-2 gap-x-1 print-no-break'>
                                <div class='w-56 pl-4'>&#10551; {color.name}</div>
                                <div class='grow flex justify-evenly text-sm'>
                                    <div class='flex w-28 justify-evenly'>
                                        <div class='td' title='Zahl der Zuchten / Züchter'>{dec( color.result.breeders )}</div>
                                        <div class='td' title='Zahl der Stämme'>{dec( color.result.pairs )}</div>
                                    </div>
                                    <div class='flex w-40 justify-evenly'>
                                        <div class='td' title='Legende Hennen'>{dec( color.result.layDames )}</div>
                                        <div class='td' title='Durchschnitt Legeleistung im Jahr'>{dec( color.result.layEggs )}</div>
                                        <div class='td'>{dec( color.result.layWeight, 1 )}</div>
                                    </div>
                                    <div class='flex w-40 justify-evenly'>
                                        <div class='td' title='Eingelegte Eier'>{dec( color.result.broodEggs )}</div>
                                        <div class='td' title='Anteil befruchteten Eier'>{pct( color.result.broodFertile, color.result.broodEggs )}</div>
                                        <div class='td' title='Anteil geschlüpfte Küken'>{pct( color.result.broodHatched , color.result.broodEggs )}</div>
                                    </div>
                                    <div class='flex w-28 justify-evenly'>
                                        <div class='td' title='Zahl der ausgestellten Tieren'>{dec( color.result.showCount )}</div>
                                        <div class='td' title='Durchschnitt Bewertungsnote'>{dec( color.result.showScore, 1 )}</div>
                                    </div>
                                </div>
                            </div>
                        {/if}
                    {/each}
                {/each}
            {/each}


            <Comment>TOTAL PER SECTION</Comment>

            <div class='flex flex-row bg-gray-300 px-2 gap-x-1 justify-evenly font-bold text-sm italic'>
                <div class='w-56'>Gesamt {section.name}</div>
                <div class='grow flex justify-evenly text-base'>
                    <div class='flex w-28 justify-evenly'>
                        <div class='td' title='Zahl der Zuchten / Züchter'>{dec( section.total.breeders )}</div>
                        <div class='td' title='Zahl der Stämme / Paare'>{dec( section.total.pairs )}</div>
                    </div>
                    <div class='flex w-40 justify-evenly'>
                        {#if section.id === 5 }
                            <div class='td'></div> <div class='td'></div> <div class='td'></div>
                        {:else}
                            <div class='td' title='Legende Hennen'>{dec( section.total.layDames )}</div>
                            <div class='td' title='Durchschnitt Legeleistung im Jahr'>{dec( section.total.layEggs )}</div>
                            <div class='td' title='Durchschnitt Eiergewicht'>{dec( section.total.layWeight, 1 )}</div>
                        {/if}
                    </div>
                    <div class='flex w-40 justify-evenly'>
                        {#if section.id === 5 }
                            <div class='td'></div>
                            <div class='td' title='Geschlüpfte Küken'>{dec( section.total.broodHatched ) }</div>
                            <div class='td' title='Zahl der Küken pro Paar'>{dec( section.total.broodResult, 1 )}</div>
                        {:else}
                            <div class='td' title='Eingelegte Eier'>{dec( section.total.broodEggs )}</div>
                            <div class='td' title='Anteil befruchteten Eier'>{pct( section.total.broodFertile, section.total.broodEggs )}</div>
                            <div class='td' title='Anteil geschlüpfte Küken'>{pct( section.total.broodHatched , section.total.broodEggs )}</div>
                        {/if}
                    </div>
                    <div class='flex w-28 justify-evenly'>
                        <div class='td' title='Zahl der ausgestellten Tieren'>{dec( section.total.showCount )}</div>
                        <div class='td' title='Durchschnitt Bewertungsnote'>{dec( section.total.showScore, 1 )}</div>
                    </div>
                </div>
            </div>
            <div class='print-break text-center'> - </div>
        {/each}
        {#if results.sections.length == 0 }
            <h2 class='p-2 bg-header text-center text-xl'>Leider keine Daten für dieses Jahr</h2>
        {/if}

    {/if}
</div>



<style>
    .th {
        @apply w-12 border-b border-gray-300 px-1 text-center;
    }
    .td {
        @apply w-12 border-b border-gray-200 px-1 text-right cursor-default;
    }
    .ts {
        @apply w-4 text-center;
    }
</style>