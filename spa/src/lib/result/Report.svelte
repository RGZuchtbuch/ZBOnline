<script>
    import { onMount } from 'svelte';
    import { dec, pct } from '../../js/util.js';

    import {meta, router} from 'tinro';

    export let district;
    export let year;
    export let report;

    function addTo( sum, result ) { // count and add all up to totals of section etc
        result.broods = result.broodEggs ? result.broodEggs / 2 : null; // for pigeons
        result.broodResult = result.pairs && result.broodHatched ? result.broodHatched / result.pairs : null;

        sum.breeders += result.breeders;
        sum.pairs += result.pairs;
        sum.layDames += result.layDames;

        if (result.layEggs) {
            sum.layers += result.breeders;
            sum.layShould += result.breeders * result.layShould; // needs div by total breeders
            sum.layEggs += result.breeders * result.layEggs; // needs div by total breeders
        }

        if (result.layWeight) {
            sum.layWeighters += result.breeders;
            sum.layWeightShould += result.breeders * result.layWeightShould;
            sum.layWeight += result.breeders * result.layWeight;
        }

        if (result.broodLayerBreeders && result.broodLayerEggs ) {
            sum.brooders += result.broodLayerBreeders;
            sum.broodEggs += result.broodLayerEggs;
            sum.broodFertile += result.breeders * result.broodLayerFertile;
            sum.broodHatched += result.breeders * result.broodLayerHatched;
        }
        if( result.broodPigeonBreeders && result.pairs && result.broodPigeonHatched ) {
            sum.brooders += result.broodPigeonBreeders;
            sum.broodChicks += result.broodPigeonHatched;
            sum.broodResult += result.breeders * result.broodPigeonProduction;
        }

        if( result.showCount && result.showScore ) {
            sum.showers += result.breeders;
            sum.showCount += result.showCount;
            sum.showScore += result.breeders * result.showScore;
        }
    }

    function avgTotal( sum ) { // get avg from total
        const total = {};
            total.breeders = sum.breeders; // reporting layers
            total.pairs = sum.pairs; // reported pigeon pairs ( could be used for layers as well )
            total.layDames = sum.layDames; // layers henns doing llaying etc, optionsl.

            total.layers = sum.layers; // breeders reporting lay results
            total.layShould = sum.layers ? sum.layShould / sum.layers : null;
            total.layEggs = sum.layers ? sum.layEggs / sum.layers : null; // to avg
            total.layWeighters = sum.layWeighters;
            total.layWeightShould = sum.layWeighters ? sum.layWeightShould / sum.layWeighters : null;
            total.layWeight = sum.layWeighters ? sum.layWeight / sum.layWeighters : null;

            //subTotal.broodEggs = subTotal.broodEggs;
            total.brooders = sum.brooders; // breeders reporting brood results ( P & L )
            total.broodEggs = sum.broodEggs;
            total.broodFertile = sum.brooders ? sum.broodFertile / sum.brooders : null; // layer
            total.broodHatched = sum.brooders ? sum.broodHatched / sum.brooders : null; // layer
            total.broodChicks = sum.broodChicks; // pigeon
            total.broodResult = sum.brooders ? sum.broodResult / sum.brooders : null; // pigeon

            // showCount
            total.showers = sum.showers; // breeders reporting showscores
            total.showCount = sum.showCount;
            total.showScore = sum.showers ? sum.showScore / sum.showers : null;
        return total;
    }

    function calcTotals( results ) {
        if( results ) {
            const resultsSum = { breeders:0, pairs:0, layDames:0, layers:0, layShould:0, layEggs:0, layWeighters:0, layWeightShould:0, layWeight:0, brooders:0, broodEggs:0, broodFertile:0, broodHatched:0, broodChicks:0, broodResult:0, showers:0, showCount:0, showScore:0 };
            for( const section of results.sections ) {
                const sectionSum = { breeders:0, pairs:0, layDames:0, layers:0, layShould:0, layEggs:0, layWeighters:0, layWeightShould:0, layWeight:0, brooders:0, broodEggs:0, broodFertile:0, broodHatched:0, broodChicks:0, broodResult:0, showers:0, showCount:0, showScore:0 };
                for( const subsection of section.subsections ) {
                    const subsectionSum = { breeders:0, pairs:0, layDames:0, layShould:0, layers:0, layEggs:0, layWeighters:0, layWeightShould:0, layWeight:0, brooders:0, broodEggs:0, broodFertile:0, broodHatched:0, broodChicks:0, broodResult:0, showers:0, showCount:0, showScore:0 };
                    for( const breed of subsection.breeds ) {
                        if( breed.result ) { // pigeons by breed
                            addTo( resultsSum, breed.result);
                            addTo( sectionSum, breed.result);
                            addTo( subsectionSum, breed.result);
                        }
                        const breedSum = { breeders:0, pairs:0, layDames:0, layShould:0, layers:0, layEggs:0, layWeighters:0, layWeightShould:0, layWeight:0, brooders:0, broodEggs:0, broodFertile:0, broodHatched:0, broodChicks:0, broodResult:0, showers:0, showCount:0, showScore:0 };
                        for( const color of breed.colors ) { // layers by color
                            if( color.result ) {
                                addTo( resultsSum, color.result);
                                addTo( sectionSum, color.result);
                                addTo( subsectionSum, color.result);
                                addTo( breedSum, color.result );
                            }
                        }
                        breed.total = avgTotal( breedSum );
                    }
                    subsection.total = avgTotal( subsectionSum );
                }
                section.total = avgTotal( sectionSum );
            }
            results.total = avgTotal( resultsSum );
        }
        console.log( 'Results', results );
    }

    function onSection( id ) {
        return () => {
            router.location.query.set( 'section', id );
        }
    }

    function onBreed( sectionId, breedId ) {
        return () => {
            router.location.query.set( 'section', sectionId );
            router.location.query.set( 'breed', breedId );
        }
    }

    function onColor( sectionId, breedId, colorId ) {
        return () => {
            router.location.query.set( 'section', sectionId );
            router.location.query.set( 'breed', breedId );
            router.location.query.set( 'color', colorId );
        }
    }

    const route = meta();

    onMount( () => {
    });

    $: calcTotals( report );
    $: console.log( 'Report', report );

</script>


<div class='print'>
    {#if report }

            {#each report.sections as section}
                <table class='w-full p-2'>
                <!-- section header -->
                <tbody>
                    <tr><th class='sticky top-0 border-y border-gray-600' colspan=14>
                        <div class='h-2 bg-white'></div>
                        <div class='flex flex-row p-2 bg-header'>
                            <small class='w-56 pr-2 text-white text-left self-end'>{#if district} {district.name} {/if}</small>
                            <div class='grow text-center text-white text-xl'>Sparte {section.name}</div>
                            <small class='w-56 pr-2 text-white text-right self-end'>{#if district} {year} {/if}</small>
                        </div>
                    </th></tr>
                    <tr>
                        <th>
                            <div class='flex flex-row bg-gray-300 px-2 gap-x-1 font-bold'>
                                <div class='grow text-left'>Gruppe, Rasse & Farbe</div>
                                <div class='flex flex-row justify-evenly gap-x-6'>
                                    <div class='w-14 text-center'>Zuchten</div>
                                    <div class='w-28 text-center'> {#if section.id === 5}-{:else}Legeleistung{/if} </div>
                                    <div class='w-40 text-center'>Brutleistung</div>
                                    <div class='w-28 text-center'>Schauleistung</div>
                                </div>
                            </div>
                            <div class='flex flex-row border-b border-gray-600 bg-gray-300 px-2 gap-x-1 text-xs'>
                                <div class='grow text-left'>Rasse & Farbe</div>
                                <div class='flex flex-row justify-evenly gap-x-6'>
                                    <div class='flex w-14 justify-evenly'>
                                        <div class='th'>Zuchten</div>
                                    </div>
                                    <div class='flex w-28 justify-evenly'>
                                        {#if section.id === 5}
                                            <div class='th'>-</div> <div class='th'>-</div>
                                        {:else}
                                            <div class='th'>Eier/J</div> <div class='th'>Gewicht</div>
                                        {/if}
                                    </div>
                                    <div class='flex w-40 justify-evenly'>
                                        {#if section.id === 5}
                                            <div class='th'>Paare</div> <div class='th'>Küken</div> <div class='th'>Kü/Pa</div>
                                        {:else}
                                            <div class='th'>Eier</div> <div class='th'>Befr.</div> <div class='th'>Küken</div>
                                        {/if}
                                    </div>
                                    <div class='flex w-28 justify-evenly'>
                                        <div class='th'>Tiere</div>
                                        <div class='th'>Punkte</div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                </tbody>

                    {#each section.subsections as subsection}
                        <!-- subsection header -->
                        <tbody>
                            <tr>
                                <th>
                                    <div class='flex flex-row mt-4 px-2 gap-x-4 font-bold text-xl text-left'>
                                        Gruppe {subsection.name}
                                    </div>
                                </th>
                            </tr>

                            <tr>
                                <td class=''>
                                    <div class='flex flex-row border-y border-gray-600 bg-gray-300 px-2 gap-x-1 text-xs'>
                                        <div class='grow text-left'>Rasse & Farbe</div>
                                        <div class='flex flex-row justify-evenly gap-x-6'>
                                            <div class='flex w-14 justify-evenly'>
                                                <div class='th'>Zuchten</div>
                                            </div>
                                            <div class='flex w-28 justify-evenly'>
                                                {#if section.id === 5}
                                                    <div class='th'>-</div> <div class='th'>-</div>
                                                {:else}
                                                    <div class='th'>Eier/J</div> <div class='th'>Gewicht</div>
                                                {/if}
                                            </div>
                                            <div class='flex w-40 justify-evenly'>
                                                {#if section.id === 5}
                                                    <div class='th'>Paare</div> <div class='th'>Küken</div> <div class='th'>Kü/Pa</div>
                                                {:else}
                                                    <div class='th'>Eier</div> <div class='th'>Befr.</div> <div class='th'>Küken</div>
                                                {/if}
                                            </div>
                                            <div class='flex w-28 justify-evenly'>
                                                <div class='th'>Tiere</div>
                                                <div class='th'>Punkte</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>

                        <!-- Breeds -->
                        {#each subsection.breeds as breed}
                            <tbody class='print-no-break'>


                                <tr>
                                    <th>
                                        <div class='flex flex-row px-2 py-1 gap-x-1 bg-gray-100'>
                                            <div class='grow text-left text-base font-semibold'>
                                                {breed.name}
                                            </div>
                                            {#if ! section.layers && breed.result}
                                                <!-- breed result for pigeon -->
                                                <div class='flex justify-evenly text-sm gap-x-6'>
                                                    <div class='flex w-14 justify-evenly'>
                                                        <div class='td' title='Zahl der Zuchten / Züchter'>{dec( breed.result.breeders )}</div>
                                                    </div>
                                                    <div class='w-28'> </div>
                                                    <div class='flex w-40 justify-evenly'>
                                                        <div class='td' title='Zahl der Brutpaare'>{dec( breed.result.pairs )}</div>
                                                        <div class='td' title='Zahl der geschlüpften Küken'>{dec( breed.result.broodPigeonHatched ) }</div>
                                                        <div class='td' title='Zahl der Küken pro Paar'>{dec( breed.result.broodPigeonProduction, 1 )}</div>
                                                    </div>
                                                    <div class='flex w-28 justify-evenly'>
                                                        <div class='td' title='Zahl der ausgestellten Tieren'>{dec( breed.result.showCount > 0 ? breed.result.showCount : null )}</div>
                                                        <div class='td' title='Durchschnitt Bewertungsnote'>{dec( breed.result.showScore, 1 )}</div>
                                                    </div>
                                                </div>
                                            {:else}
                                                <!-- breed total for layers -->
                                                <div class='flex justify-evenly text-sm gap-x-6'>
                                                    <div class='flex w-14 justify-evenly'>
                                                        <div class='td' title='Zahl der Zuchten / Züchter'>{dec( breed.total.breeders )}</div>
                                                    </div>
                                                    <div class='flex w-28 justify-evenly'>
                                                        <div class='td' title='Durchschnitt Legeleistung im Jahr'>{pct(breed.total.layEggs, 1, 1)}</div>
                                                        <div class='td' title='Durchschnitt Eiergewicht'>{pct( breed.total.layWeight, 1, 1 )}</div>
                                                    </div>
                                                    <div class='flex w-40 justify-evenly'>
                                                            <div class='td' title='Eingelegte Eier'>{dec( breed.total.broodEggs )}</div>
                                                            <div class='td' title='Anteil befruchteten Eier'>{pct( breed.total.broodFertile, 1 )}</div>
                                                            <div class='td' title='Anteil geschlüpfte Küken'>{pct( breed.total.broodHatched, 1 )}</div>
                                                    </div>
                                                    <div class='flex w-28 justify-evenly'>
                                                        <div class='td' title='Zahl der ausgestellten Tieren'>{dec( breed.total.showCount > 0 ? breed.total.showCount : null )}</div>
                                                        <div class='td' title='Durchschnitt Bewertungsnote'>{dec( breed.total.showScore, 1 )}</div>
                                                    </div>
                                                </div>
                                            {/if}
                                        </div>
                                    </th>
                                </tr>


                                <!-- Colors -->
                                {#each breed.colors as color}
                                    {#if section.id !== 5 && color.result}
                                        <tr>
                                            <td>
                                                <div class='flex flex-row px-2 gap-x-1 print-no-break'>
                                                    <div class='grow pl-4'>
                                                        &#10551; {color.name || color.result.aocColor}
                                                    </div>
                                                    <div class='flex justify-evenly text-sm gap-x-6'>
                                                        <div class='flex w-14 justify-evenly'>
                                                            <div class='td' title='Zahl der Zuchten / Züchter'>{dec( color.result.breeders )}</div>
                                                        </div>
                                                        <div class='flex w-28 justify-evenly'>
                                                            <div class='td' title='Durchschnitt Legeleistung im Jahr'>{pct( color.result.layEggs, 1 )}</div>
                                                            <div class='td'>{pct( color.result.layWeight, 1, 1 )}</div>
                                                        </div>
                                                        <div class='flex w-40 justify-evenly'>
                                                            <div class='td' title='Eingelegte Eier'>{dec( color.result.broodLayerEggs )}</div>
                                                            <div class='td' title='Anteil befruchteten Eier'>{pct( color.result.broodLayerFertile, 1 )}</div>
                                                            <div class='td' title='Anteil geschlüpfte Küken'>{pct( color.result.broodLayerHatched, 1 )}</div>
                                                        </div>
                                                        <div class='flex w-28 justify-evenly'>
                                                            <div class='td' title='Zahl der ausgestellten Tieren'>{dec( color.result.showCount > 0 ? color.result.showCount : null )}</div>
                                                            <div class='td' title='Durchschnitt Bewertungsnote'>{dec( color.result.showScore, 1 )}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    {/if}
                                {/each} <!-- color -->
                            </tbody>
                        {/each} <!-- breed -->
                        {#if subsection.aoc}
                            {#each subsection.aoc as breed}
                                <tbody>
                                    <tr><td>AOC {breed.name}</td></tr>
                                </tbody>
                            {/each}
                        {/if}


                        <!-- total subsection -->
                        <tbody>
                            <tr>
                                <td>
                                    <div class='flex flex-row border-y border-gray-600 bg-gray-300 my-1 px-2 gap-x-1 justify-evenly text-sm italic'>
                                        <div class='grow'>Gesamt {subsection.name}</div>
                                        <div class='flex justify-evenly gap-x-6'>
                                            <div class='flex w-14 justify-evenly'>
                                                <div class='td' title='Zahl der Zuchten / Züchter'>{dec( subsection.total.breeders )}</div>
                                            </div>
                                            <div class='flex w-28 justify-evenly'>
                                                {#if section.id === 5 }
                                                    <div class='td'>-</div> <div class='td'>-</div>
                                                {:else}
                                                    <div class='td' title='Durchschnitt Legeleistung im Jahr'>{pct(subsection.total.layEggs, 1, 1)}</div>
                                                    <div class='td' title='Durchschnitt Eiergewicht'>{pct( subsection.total.layWeight, 1, 1 )}</div>
                                                {/if}
                                            </div>
                                            <div class='flex w-40 justify-evenly'>
                                                {#if ! section.layers }
                                                    <div class='td' title='Paare'>{dec( subsection.total.pairs )}</div>
                                                    <div class='td' title='Geschlüpfte Küken'>{dec( subsection.total.broodChicks ) }</div>
                                                    <div class='td' title='Zahl der Küken pro Paar'>{dec( subsection.total.broodResult, 1 )}</div>
                                                {:else}
                                                    <div class='td' title='Eingelegte Eier'>{dec( subsection.total.broodEggs )}</div>
                                                    <div class='td' title='Anteil befruchteten Eier'>{pct( subsection.total.broodFertile, 1 )}</div>
                                                    <div class='td' title='Anteil geschlüpfte Küken'>{pct( subsection.total.broodHatched, 1 )}</div>
                                                {/if}
                                            </div>
                                            <div class='flex w-28 justify-evenly'>
                                                <div class='td' title='Zahl der ausgestellten Tieren'>{dec( subsection.total.showCount > 0 ? subsection.total.showCount : null )}</div>
                                                <div class='td' title='Durchschnitt Bewertungsnote'>{dec( subsection.total.showScore, 1 )}</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    {/each} <!-- subsection -->


                <tbody>
                    <!-- section total -->
                    <tr>
                        <th>
                            <div class='flex flex-row bg-header text-white px-2 gap-x-1 justify-evenly font-bold text-sm italic border-y border-gray-600'>
                                <div class='grow'>Gesamt {section.name}</div>
                                <div class='flex justify-evenly text-sm gap-x-6'>
                                    <div class='flex w-14 justify-evenly'>
                                        <div class='td' title='Zahl der Zuchten / Züchter'>{dec( section.total.breeders )}</div>
                                    </div>
                                    <div class='flex w-28 justify-evenly'>
                                        {#if section.id === 5 }
                                            <div class='td'>-</div> <div class='td'>-</div>
                                        {:else}
                                            <div class='td' title='Durchschnitt Legeleistung im Jahr'>{pct( section.total.layEggs, 1, 1)}</div>
                                            <div class='td' title='Durchschnitt Eiergewicht'>{pct( section.total.layWeight, 1, 1 )}</div>
                                        {/if}
                                    </div>

                                    <div class='flex w-40 justify-evenly'>
                                        {#if section.id === 5 }
                                            <div class='td' title='Paare'>{dec( section.total.pairs )}</div>
                                            <div class='td' title='Geschlüpfte Küken'>{dec( section.total.broodChicks ) }</div>
                                            <div class='td' title='Zahl der Küken pro Paar'>{dec( section.total.broodResult, 1 )}</div>
                                        {:else}
                                            <div class='td' title='Eingelegte Eier'>{dec( section.total.broodEggs )}</div>
                                            <div class='td' title='Anteil befruchteten Eier'>{pct( section.total.broodFertile, 1 )}</div>
                                            <div class='td' title='Anteil geschlüpfte Küken'>{pct( section.total.broodHatched, 1 )}</div>
                                        {/if}
                                    </div>

                                    <div class='flex w-28 justify-evenly'>
                                        <div class='td' title='Zahl der ausgestellten Tieren'>{dec( section.total.showCount > 0 ? section.total.showCount : null )}</div>
                                        <div class='td' title='Durchschnitt Bewertungsnote'>{dec( section.total.showScore, 1 )}</div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <!-- end section total -->


                </tbody>
                {#if section.id === report.sections[ report.sections.length-1 ].id } <!-- last section -->
                    <tbody>
                        <!-- totals -->
                        <tr><th>-</th></tr>

                        <tr><th class='sticky top-0 border-y border-gray-600 p-2 bg-header text-center text-white text-xl' colspan=14>Gesammt Geflügel</th></tr>
                        <tr>
                            <th>
                                <div class='flex flex-row bg-gray-300 px-2 gap-x-1 font-bold'>
                                    <div class='grow text-left'>Alle Sparten, Gruppen, Rassen & Farben</div>
                                    <div class='flex flex-row justify-evenly gap-x-6'>
                                        <div class='w-14 text-center'>Zuchten</div>
                                        <div class='w-28'></div>
                                        <div class='w-40'></div>
                                        <div class='w-28 text-center'>Schauleistung</div>
                                    </div>
                                </div>
                                <div class='flex flex-row bg-gray-300 px-2 gap-x-1 text-xs'>
                                    <div class='grow text-left'></div>
                                    <div class='flex flex-row justify-evenly gap-x-6'>
                                        <div class='flex w-14 justify-evenly'>
                                            <div class='th'></div>
                                        </div>
                                        <div class='w-28'></div>
                                        <div class='w-40'></div>
                                        <div class='flex w-28 justify-evenly'>
                                            <div class='th'>Tiere</div>
                                            <div class='th'>Punkte</div>
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <div class='flex flex-row bg-header text-white px-2 gap-x-1 justify-evenly font-bold text-sm italic border-y border-gray-600'>
                                    <div class='grow'>Gesamt</div>
                                    <div class='flex justify-evenly text-sm gap-x-6'>
                                        <div class='flex w-14 justify-evenly'>
                                            <div class='td' title='Zahl der Zuchten / Züchter'>{dec( report.total.breeders )}</div>
                                        </div>
                                        <div class='w-28'></div>

                                        <div class='w-40'></div>

                                        <div class='flex w-28 justify-evenly'>
                                            <div class='td' title='Zahl der ausgestellten Tieren'>{dec( report.total.showCount )}</div>
                                            <div class='td' title='Durchschnitt Bewertungsnote'>{dec( report.total.showScore, 1 )}</div>
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </tbody>
                {/if}

                <div class='print-break text-center'> - </div>
                </table>
            {/each}
        {#if report.sections.length === 0 }
            <h2 class='p-2 bg-header text-center text-xl'>Leider keine Daten für dieses Jahr</h2>
        {/if}

    {/if}
</div>



<style>
    table, tr, th, td {
        @apply border-collapse border-0 p-0 m-0;
    }
    th, td {
        @apply px-0;
    }
    .th {
        @apply w-12 text-center;
    }
    .td {
        @apply w-12 text-right cursor-default;
    }

</style>