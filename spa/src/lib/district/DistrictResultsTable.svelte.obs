<script>
    import { onMount } from 'svelte';
    import { dec, pct } from '../../js/util.js';

    import {meta, router} from 'tinro';

    import Comment from '../common/Comment.svelte';

    export let results;

    const route = meta();

    onMount( () => {
    });

    function addTo( sum, result ) {

        sum.breeders += result.breeders;
        sum.pairs += result.pairs;

        sum.layDames += result.layDames;


        if (result.layBreeders) { // layers only, if provided eggs count
            sum.layBreeders += result.layBreeders; // total
            sum.layShould += result.layBreeders * result.layShould; // avg
            sum.layEggs += result.layBreeders * result.layEggs; // avg
        }

        if (result.layWeightBreeders) { // layers only, if provide egg weight
            sum.layWeightBreeders += result.layWeightBreeders;  // total
            sum.layWeightShould += result.layWeightBreeders * result.layWeightShould; // avg
            sum.layWeight += result.layWeightBreeders * result.layWeight; // avg
        }

        if( result.broodPigeonBreeders ) {
            sum.broodBreeders += result.broodPigeonBreeders; // total
            // future with nr of broods/nests and 2 eggs per nest ?
            sum.broodPigeonBreeders += result.broodPigeonBreeders;
            sum.broodPigeonHatched += result.broodPigeonHatched;     // total
            sum.broodPigeonProduction += result.broodPigeonBreeders * result.broodPigeonProduction; // count * avg
        }

        if (result.broodLayerBreeders) { // layers only
            sum.broodBreeders += result.broodLayerBreeders; // total
            sum.broodLayerBreeders += result.broodLayerBreeders; // total
            sum.broodLayerEggs += result.broodLayerEggs; // total
            sum.broodLayerFertileBreeders += result.broodLayerFertile ? result.broodLayerBreeders : 0;
            sum.broodLayerFertile += result.broodLayerFertile ? result.broodLayerBreeders * result.broodLayerFertile : 0; // count * avg
            sum.broodLayerHatchedBreeders += result.broodLayerHatched ? result.broodLayerBreeders : 0;
            sum.broodLayerHatched += result.broodLayerHatched ? result.broodLayerBreeders * result.broodLayerHatched : 0; // count * avg
        }

        if( result.showBreeders ) {
            sum.showBreeders += result.showBreeders; // count
            sum.showCount += result.showCount; // count
            sum.showScore += result.showBreeders * result.showScore; // count * avg
        }

        // pigeon ?? needed ?
        //result.broods = result.broodEggs ? result.broodEggs / 2 : null; // for pigeons
        //result.broodProduction = result.pairs && result.broodHatched ? result.broodHatched / result.pairs : null;
    }



    function calcTotals( results ) {
        console.log( 'Results A', results )
        if( results ) {
            for( const section of results.sections ) {
                let total = { breeders:0, pairs:0, layDames:0, layers:0, layShould:0, layEggs:0, layWeighters:0, layWeightShould:0, layWeight:0, brooders:0, broodEggs:0, broodFertile:0, broodHatched:0, broodChicks:0, broodProduction:0, showers:0, showCount:0, showScore:0 };
                for( const subSection of section.subsections ) {
                    let subTotal = { breeders:0, pairs:0, layDames:0, layShould:0, layers:0, layEggs:0, layWeighters:0, layWeightShould:0, layWeight:0, brooders:0, broodEggs:0, broodFertile:0, broodHatched:0, broodChicks:0, broodProduction:0, showers:0, showCount:0, showScore:0 };
                    for( const breed of subSection.breeds ) {
                        if( breed.result ) { // pigeon
                            addTo( total, breed.result);
                            addTo( subTotal, breed.result);
                        }

                        let breedTotal = { breeders:0, pairs:0, layDames:0, layShould:0, layers:0, layEggs:0, layWeighters:0, layWeightShould:0, layWeight:0, brooders:0, broodEggs:0, broodFertile:0, broodHatched:0, broodChicks:0, broodProduction:0, showers:0, showCount:0, showScore:0 };
                        for( const color of breed.colors ) {
                            if( color.result ) { // layer
                                addTo( total, color.result);
                                addTo( subTotal, color.result);
                                addTo( breedTotal, color.result);
                            }
                        }
                    }
                    subTotal.layShould = subTotal.layers ? subTotal.layShould / subTotal.layers : null;
                    subTotal.layEggs = subTotal.layers ? subTotal.layEggs / subTotal.layers : null; // to avg
                    subTotal.layWeightShould = subTotal.layWeighters ? subTotal.layWeightShould / subTotal.layWeighters : null;
                    subTotal.layWeight = subTotal.layWeighters ? subTotal.layWeight / subTotal.layWeighters : null;

                    //subTotal.broodEggs = subTotal.broodEggs;
                    subTotal.broodFertile = subTotal.brooders ? subTotal.broodFertile / subTotal.brooders : null;
                    subTotal.broodHatched = subTotal.brooders ? subTotal.broodHatched / subTotal.brooders : null;

                    //subTotal.pairs
                    //subTotal.broodChicks
                    subTotal.broodProduction = subTotal.brooders ? subTotal.broodProduction / subTotal.brooders : null; // avg of ch/pair per breeder.

                    // showCount
                    subTotal.showScore = subTotal.showers ? subTotal.showScore / subTotal.showers : null;
                    subSection.total = subTotal;
                    console.log( 'ST', subTotal );
                }
                total.layShould = total.layers ? total.layShould / total.layers : null;
                total.layEggs = total.layers ? total.layEggs / total.layers : null;
                total.layWeightShould = total.layWeighters ? total.layWeightShould / total.layWeighters : null;
                total.layWeight = total.layWeighters ? total.layWeight / total.layWeighters : null;

                // total.broodEggs
                total.broodFertile = total.brooders ? total.broodFertile / total.brooders : null;
                total.broodHatched = total.brooders ? total.broodHatched / total.brooders : null;
                // total pairs
                // total broodChicks
                total.broodProduction = total.brooders ? total.broodProduction / total.brooders : null; // avg of ch/pair per breeder.

                total.showScore = total.showers ? total.showScore / total.showers : null;
                section.total = total;
                console.log( 'ST', total );
            }
        }
        console.log( "Results B", results );

    }

//    $: calcTotals( results );

    function toWeightedAverage( sum ) {
        return {
            breeders        : sum.breeders,
            pairs                   : sum.pairs,

            // lay + weight
            layDames        : sum.layDames, // count henns
            layEggs         : sum.layBreeders ? sum.layEggs / sum.layBreeders : null,
            layShould       : sum.layBreeders ? sum.layShould / sum.layBreeders : null,

            layWeight       : sum.layWeightBreeders ? sum.layWeight / sum.layWeightBreeders : null,
            layWeightShould : sum.layWeightBreeders ? sum.layWeightShould / sum.layWeightBreeders : null,

            // brood
            broodBreeders           : sum.broodBreeders,
            // pigeons
            broodPigeonBreeders     : sum.broodPigeonBreeders,
            broodPigeonHatched      : sum.broodPigeonHatched, // pigeon
            broodPigeonProduction   : sum.broodPigeonBreeders ? sum.broodPigeonProduction / sum.broodPigeonBreeders : null, // pigeon
            // layers
            broodLayerBreeders      : sum.broodLayerBreeders,
            broodLayerEggs          : sum.broodLayerEggs,
            broodLayerFertile       : sum.broodLayerFertileBreeders ? sum.broodLayerFertile / sum.broodLayerFertileBreeders : null,
            broodLayerHatched       : sum.broodLayerHatchedBreeders ? sum.broodLayerHatched / sum.broodLayerHatchedBreeders : null,

            // show
            showBreeders            : sum.showBreeders,
            showCount               : sum.showCount,
            showScore               : sum.showBreeders ? sum.showScore / sum.showBreeders : null,
        }
    }

    const sum  = { breeders:0, pairs:0,
        layBreeders:0, layDames:0, layShould:0, layEggs:0, layWeightBreeders:0, layWeightShould:0, layWeight:0,
        broodBreeders:0,
        broodPigeonBreeders:0, broodPigeonHatched:0, broodPigeonProduction:0,
        broodLayerBreeders:0, broodLayerEggs:0, broodLayerFertileBreeders:0, broodLayerFertile:0, broodLayerHatchedBreeders:0, broodLayerHatched:0,
        showBreeders:0, showCount:0, showScore:0 };

    function getTotals( results ) {
        if( results ) {
            const resultsSum = { ...sum };
            for( const section of results.sections ) {
                const sectionSum = { ...sum };
                for( const subsection of section.subsections ) {
                    const subsectionSum = { ...sum };
                    for( const breed of subsection.breeds ) {
                        if( breed.result ) { // if layers gef breed result from colors.
                            addTo( subsectionSum, breed.result );
                            addTo( sectionSum, breed.result );
                            addTo( resultsSum, breed.result);
                        } else {
//                            const breedSum = { breeders:0, pairs:0, layDames:0, layers:0, layShould:0, layEggs:0, layWeighters:0, layWeightShould:0, layWeight:0, brooders:0, broodEggs:0, broodFertile:0, broodHatched:0, broodChicks:0, broodProduction:0, showers:0, showCount:0, showScore:0 };
                            const breedSum = { ...sum };
                            for (const color of breed.colors) {
                                if( color.result ) {
                                    addTo( breedSum, color.result );
                                    addTo( subsectionSum, color.result );
                                    addTo( sectionSum, color.result );
                                    addTo( resultsSum, color.result );
                                }
                            }
                            breed.result = toWeightedAverage( breedSum );
//                            console.log( 'BreedSum', breedSum );
//                            console.log( 'BreedResult', breed.result );
                        }
                    }
                    subsection.result = toWeightedAverage( subsectionSum );
//                    console.log( 'SubSectionSum', subsectionSum );
//                    console.log( 'SubSectionResult', subsection.result );
                }
                section.result = toWeightedAverage( sectionSum );
            }
            results.result = toWeightedAverage( resultsSum );
        }
        console.log( 'Results after', results );
    }

    $: getTotals( results );
</script>


<div class='print'>
    {#if results }
        {#each results.sections as section}
            <table class='w-full p-2'>
                <!-- Section header -->
                <thead>
                    <tr><th class='sticky top-0 p-2 bg-header text-center text-white text-xl' colspan=14>Sparte {section.name}</th></tr>
                    <tr>
                        <th>
                            <div class='flex flex-row border-t border-gray-800 bg-gray-300 px-2 gap-x-1 font-bold'>
                                <div class='w-64 text-left'>Gruppe / Rasse / Farbe</div>
                                <div class='grow flex flex-row justify-evenly'>
                                    <div class='w-14 text-center'>Zuchten</div>
                                    <div class='w-40 text-center text-center'> {#if section.id === 5}-{:else}Legeleistung{/if} </div>
                                    <div class='w-40 text-center'>Brutleistung</div>
                                    <div class='w-28 text-center'>Schauleistung</div>
                                </div>
                            </div>
                            <div class='flex flex-row border-b border-gray-800 bg-gray-300 px-2 gap-x-1 text-xs'>
                                <div class='w-64 text-left'>Rasse & Farbe</div>
                                <div class='grow flex flex-row justify-evenly'>
                                    <div class='flex w-14 justify-evenly'>
                                        <div class='th'>Zuchten</div>
                                    </div>
                                    <div class='flex w-40 justify-evenly'>
                                        {#if section.id === 5}
                                            <div class='th'>-</div> <div class='th'>-</div> <div class='th'>-</div>
                                        {:else}
                                            <div class='th'>Soll</div> <div class='th'>Eier/J</div> <div class='th'>Gewicht</div>
                                        {/if}
                                    </div>
                                    <div class='flex w-40 justify-evenly'>
                                        {#if section.id === 5}
                                            <div class='th'>Paare</div> <div class='th'>Kü/Pa</div> <div class='th'>Küken</div>
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
                </thead>

                <tbody>
                    {#each section.subsections as subsection}
                        <!-- subsection header -->
                        <tr>
                            <th>
                                <div class='flex flex-row mt-4 px-2 gap-x-4 font-bold text-xl text-left' >
                                    {subsection.name}
                                </div>
                            </th>
                        </tr>

                        <tr>
                            <td class=''>
                                <div class='flex flex-row border-y border-gray-800 bg-gray-300 px-2 gap-x-1 text-xs'>
                                    <div class='w-64 text-left'>Rasse & Farbe</div>
                                    <div class='grow flex flex-row justify-evenly'>
                                        <div class='flex w-14 justify-evenly'>
                                            <div class='th'>Zuchten</div>
                                        </div>
                                        <div class='flex w-40 justify-evenly'>
                                            {#if section.id === 5}
                                                <div class='th'>-</div> <div class='th'>-</div> <div class='th'>-</div>
                                            {:else}
                                                <div class='th'>Soll</div> <div class='th'>Eier/J</div> <div class='th'>Gewicht</div>
                                            {/if}
                                        </div>
                                        <div class='flex w-40 justify-evenly'>
                                            {#if section.id === 5}
                                                <div class='th'>Paare</div> <div class='th'>Kü/Pa</div> <div class='th'>Küken</div>
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

                        {#each subsection.breeds as breed, i}
                            <!-- breed result -->
                            <tr>
                                <th>
                                    <div class='flex flex-row px-2 py-1 gap-x-1 bg-gray-100'>
                                        <div class='w-64 text-left text-base font-semibold' >
                                            {breed.name}
                                        </div>
                                            <div class='grow flex justify-evenly text-sm font-semibold'>
                                                <div class='flex w-14 justify-evenly'>
                                                    <div class='td' title='Zahl der Zuchten / Züchter'>{dec( breed.result.breeders )}</div>
                                                </div>
                                                {#if section.id === 5 }
                                                    <div class='w-40'> </div>
                                                    <div class='flex w-40 justify-evenly'>
                                                        <div class='td'>{dec( breed.result.pairs )}</div>
                                                        <div class='td' title='Zahl der Küken pro Paar'>{dec( breed.result.broodPigeonProduction, 1 )}</div>
                                                        <div class='td' title='Geschlüpfte Küken'>{dec( breed.result.broodPigeonHatched )}</div>
                                                    </div>
                                                {:else}
                                                    <div class='flex w-40 justify-evenly'>
                                                        <div class='td' title='Soll Legeleistung im Jahr'>{dec( breed.result.layShould )}</div>
                                                        <div class='td' title='Durchschnitt Legeleistung im Jahr'>{pct( breed.result.layEggs, 1 )}</div>
                                                        <div class='td'>{pct( breed.result.layWeight, 1, 1 )}</div>
                                                    </div>
                                                    <div class='flex w-40 justify-evenly'>
                                                        <div class='td' title='Eingelegte Eier'>{dec( breed.result.broodLayerEggs )}</div>
                                                        <div class='td' title='Anteil befruchteten Eier'>{pct( breed.result.broodLayerFertile, 1 )}</div>
                                                        <div class='td' title='Anteil geschlüpfte Küken'>{pct( breed.result.broodLayerHatched, 1 )}</div>
                                                    </div>
                                                {/if}

                                                <div class='flex w-28 justify-evenly'>
                                                    <div class='td' title='Zahl der ausgestellten Tieren'>{dec( breed.result.showCount > 0 ? breed.result.showCount : null )}</div>
                                                    <div class='td' title='Durchschnitt Bewertungsnote'>{dec( breed.result.showScore, 1 )}</div>
                                                </div>
                                            </div>
                                    </div>
                                </th>
                            </tr>

                            {#each breed.colors as color}
                                {#if color.result}
                                    <!-- color result for layers -->
                                    <tr>
                                        <td>
                                            <div class='flex flex-row px-2 gap-x-1 print-no-break'>
                                                <div class='w-64 pl-4' >
                                                    &#10551; {color.name}
                                                </div>
                                                <div class='grow flex justify-evenly text-sm'>
                                                    <div class='flex w-14 justify-evenly'>
                                                        <div class='td' title='Zahl der Zuchten / Züchter'>{dec( color.result.breeders )}</div>
                                                    </div>
                                                    <div class='flex w-40 justify-evenly'>
                                                        <div class='td' title='Soll Legeleistung im Jahr'>{dec( color.result.layShould )}</div>
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


                        {/each} <!-- subsection -->

                        <!-- total subsection -->
                        <tr>
                            <td>
                                <div class='flex flex-row border-y border-gray-800 bg-gray-300 my-1 px-2 gap-x-1 justify-evenly text-sm italic'>
                                    <div class='w-64'>Gesamt {subsection.name}</div>
                                    <div class='grow flex justify-evenly'>
                                        <div class='flex w-14 justify-evenly'>
                                            <div class='td' title='Zahl der Zuchten / Züchter'>{dec( subsection.result.breeders )}</div>
                                        </div>
                                        <!-- lay -->
                                        <div class='flex w-40 justify-evenly'>
                                            {#if section.id === 5 }
                                                <div class='td'>-</div> <div class='td'>-</div> <div class='td'>-</div>
                                            {:else}
                                                <div class='td' title='Soll Legeleistung im Jahr'>{dec( subsection.result.layShould )}</div>
                                                <div class='td' title='Durchschnitt Legeleistung im Jahr'>{pct(subsection.result.layEggs, 1, 1)}</div>
                                                <div class='td' title='Durchschnitt Eiergewicht'>{pct( subsection.result.layWeight, 1, 1 )}</div>
                                            {/if}
                                        </div>

                                        <!-- brood -->
                                        <div class='flex w-40 justify-evenly'>
                                            {#if section.id === 5 }
                                                <div class='td' title='Paare'>{dec( subsection.result.pairs )}</div>
                                                <div class='td' title='Zahl der Küken pro Paar'>{dec( subsection.result.broodPigeonProduction, 1 )}</div>
                                                <div class='td' title='Geschlüpfte Küken'>{dec( subsection.result.broodPigeonHatched ) }</div>
                                            {:else}
                                                <div class='td' title='Eingelegte Eier'>{dec( subsection.result.broodLayerEggs )}</div>
                                                <div class='td' title='Anteil befruchteten Eier'>{pct( subsection.result.broodLayerFertile, 1 )}</div>
                                                <div class='td' title='Anteil geschlüpfte Küken'>{pct( subsection.result.broodLayerHatched, 1 )}</div>
                                            {/if}
                                        </div>
                                        <div class='flex w-28 justify-evenly'>
                                            <div class='td' title='Zahl der ausgestellten Tieren'>{dec( subsection.result.showCount )}</div>
                                            <div class='td' title='Durchschnitt Bewertungsnote'>{dec( subsection.result.showScore, 1 )}</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    {/each}



                </tbody>

                <tfoot>
                <!-- total section -->
                    <tr>
                        <th>
                            <div class='flex flex-row bg-header text-white px-2 gap-x-1 justify-evenly font-bold text-sm italic border-y border-gray-800'>
                                <div class='w-64'>Gesamt {section.name}</div>
                                <div class='grow flex justify-evenly text-sm'>
                                    <div class='flex w-14 justify-evenly'>
                                        <div class='th' title='Zahl der Zuchten / Züchter'>{dec( section.result.breeders )}</div>
                                    </div>
                                    <div class='flex w-40 justify-evenly'>
                                        {#if section.id === 5 }
                                            <div class='td'>-</div> <div class='td'>-</div> <div class='td'>-</div>
                                        {:else}
                                            <div class='td' title='Soll Legeleistung im Jahr'>{dec(section.result.layShould)}</div>
                                            <div class='td' title='Durchschnitt Legeleistung im Jahr'>{pct( section.result.layEggs, 1, 1)}</div>
                                            <div class='td' title='Durchschnitt Eiergewicht'>{pct( section.result.layWeight, 1, 1 )}</div>
                                        {/if}
                                    </div>

                                    <div class='flex w-40 justify-evenly'>
                                        {#if section.id === 5 }
                                            <div class='td' title='Paare'>{dec( section.result.pairs )}</div>
                                            <div class='td' title='Zahl der Küken pro Paar'>{dec( section.result.broodPigeonProduction, 1 )}</div>
                                            <div class='td' title='Geschlüpfte Küken'>{dec( section.result.broodPigeonHatched ) }</div>
                                        {:else}
                                            <div class='td' title='Eingelegte Eier'>{dec( section.result.broodLayerEggs )}</div>
                                            <div class='td' title='Anteil befruchteten Eier'>{pct( section.result.broodLayerFertile, 1 )}</div>
                                            <div class='td' title='Anteil geschlüpfte Küken'>{pct( section.result.broodLayerHatched, 1 )}</div>
                                        {/if}
                                    </div>

                                    <div class='flex w-28 justify-evenly'>
                                        <div class='td' title='Zahl der ausgestellten Tieren'>{dec( section.result.showCount )}</div>
                                        <div class='td' title='Durchschnitt Bewertungsnote'>{dec( section.result.showScore, 1 )}</div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                </tfoot>

            </table>
            <div class='print-break text-center'> - </div>
        {/each} <!-- sections -->

        <table class='w-full p-2'>
            <tbody>
            <tr>
                <th>
                    <div class='flex flex-row bg-header text-white px-2 gap-x-1 justify-evenly font-bold text-sm italic border-y border-gray-800'>
                        <div class='w-64'>Alle Sparten gesammt</div>
                        <div class='grow flex justify-evenly text-sm'>
                            <div class='flex w-14 justify-evenly'>
                                <div class='td' title='Zahl der Zuchten / Züchter'>{dec( results.result.breeders )}</div>
                            </div>
                            <div class='flex w-40 justify-evenly'>
                                <div class='td'>-</div> <div class='td'>-</div> <div class='td'>- </div>
                            </div>

                            <div class='flex w-40 justify-evenly'>
                                    <div class='td'>-</div><div class='td'>-</div>
                                    <div class='td' title='Küken'>{dec( results.result.broodLayerEggs * results.result.broodLayerHatched + results.result.broodPigeonHatched, 0 )}</div>
                            </div>

                            <div class='flex w-28 justify-evenly'>
                                <div class='td' title='Zahl der ausgestellten Tieren'>{dec( results.result.showCount )}</div>
                                <div class='td' title='Durchschnitt Bewertungsnote'>{dec( results.result.showScore, 1 )}</div>
                            </div>
                        </div>
                    </div>
                </th>
            </tr>
            </tbody>
        </table>

        {#if results.sections.length == 0 }
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
        @apply w-12 border-b border-gray-300 text-right cursor-default;
    }
    .ts {
        @apply w-4 text-center;
    }
</style>