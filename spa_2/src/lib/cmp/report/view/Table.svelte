<script>
    import {fade} from 'svelte/transition';
    import { cfg } from '$lib/js/store.svelte.js';
    import { dec, pct } from '$lib/js/tools.js';

    let { data, district, year } = $props();

    let totalled = $state( calcTotals() );

   // in effect makes it trigger twice, now in #key block. should be in onMount ?

    function addTo( sum, result ) { // count and add all up to totals of section etc
        result.broods = result.broodEggs ? result.broodEggs / 2 : null; // for pigeons
        result.broodResult = result.pairs && result.broodHatched ? result.broodHatched / result.pairs : null;

        sum.breeders += result.breeders;
        sum.pairs += result.pairs;
        sum.layDames += result.layDames;

        if (result.layEggs) {
            sum.layBreeders += result.layBreeders;
            sum.layShould   += result.layBreeders * result.layShould; // needs div by total breeders
            sum.layEggs     += result.layBreeders * result.layEggs; // needs div by total breeders
        }

        if (result.layWeight) {
            sum.layWeightBreeders += result.layWeightBreeders;
            sum.layWeightShould   += result.layWeightBreeders * result.layWeightShould;
            sum.layWeight         += result.layWeightBreeders * result.layWeight;
        }

        if( result.broodLayerEggs ) {
            sum.broodBreeders     += result.broodLayerBreeders;
            sum.broodLayerEggs    += result.broodLayerEggs;
            sum.broodLayerFertile += result.broodLayerBreeders * result.broodLayerFertile;
            sum.broodLayerHatched += result.broodLayerBreeders * result.broodLayerHatched;
        }
        if( result.pairs && result.broodPigeonHatched ) { // TODO could jest check on eggs, like layers !!
            sum.broodBreeders      += result.broodPigeonBreeders;
            sum.broodPigeonEggs    += result.broodPigeonEggs;
            sum.broodPigeonHatched += result.broodPigeonBreeders * result.broodPigeonHatched;
            sum.broodPigeonResult  += result.broodPigeonBreeders * result.broodPigeonResult;
        }

        if( result.showCount && result.showScore ) {
            sum.showBreeders += result.showBreeders;
            sum.showCount += result.showCount;
            sum.showScore += result.showBreeders * result.showScore;
        }
    }
    function avgTotal( sum ) { // get avg from total
        const total = {};
            total.breeders = sum.breeders; // reporting layers
            total.pairs = sum.pairs; // reported pigeon pairs ( could be used for layers as well )

            total.layDames = sum.layDames; // layers henns doing llaying etc, optionsl.
            total.layBreeders = sum.layBreeders; // breeders reporting lay results
            total.layShould = sum.layBreeders ? sum.layShould / sum.layBreeders : null;
            total.layEggs = sum.layBreeders ? sum.layEggs / sum.layBreeders : null; // to avg
            total.layWeightBreeders = sum.layWeightBreeders;
            total.layWeightShould = sum.layWeightBreeders ? sum.layWeightShould / sum.layWeightBreeders : null;
            total.layWeight = sum.layWeightBreeders ? sum.layWeight / sum.layWeightBreeders : null;

            //subTotal.broodEggs = subTotal.broodEggs;
            total.broodBreeders = sum.broodBreeders; // breeders reporting brood results ( P & L )

            total.broodLayerEggs = sum.broodLayerEggs;
            total.broodLayerFertile = sum.broodBreeders ? sum.broodLayerFertile / sum.broodBreeders : null; // layer
            total.broodLayerHatched = sum.broodBreeders ? sum.broodLayerHatched / sum.broodBreeders : null; // layer

            total.broodPigeonEggs = sum.broodPigeonEggs;
            //total.broodPigeonHatched = sum.broodPigeonHatched; // pigeon
            total.broodPigeonHatched = sum.broodBreeders ? sum.broodPigeonHatched / sum.broodBreeders : null; // pigeon
            total.broodPigeonResult = sum.broodBreeders ? sum.broodPigeonResult / sum.broodBreeders : null; // pigeon

            // showCount
            total.showBreeders = sum.showBreeders; // breeders reporting showscores
            total.showCount = sum.showCount;
            total.showScore = sum.showBreeders ? sum.showScore / sum.showBreeders : null;
        return total;
    }
    function createTotal() {
        return { breeders:0, pairs:0, layDames:0, layShould:0, layBreeders:0, layEggs:0, layWeightBreeders:0, layWeightShould:0, layWeight:0, broodBreeders:0, broodLayerEggs:null, broodLayerFertile:0, broodLayerHatched:0, broodPigeonEggs:null, broodPigeonHatched:null, broodPigeonResult:0, showBreeders:0, showCount:null, showScore:0 };
    }
    // function calcTotals(  ) {
    //     totalledReport = data;
    //     const resultsSum = createTotal();
    //     for( const section of totalledReport.sections ) {
    //         const sectionSum = createTotal();
    //         for( const subsection of section.subsections ) {
    //             const subsectionSum = createTotal();
    //             for( const breed of subsection.breeds ) {
    //                 if( breed.result ) { // pigeons by breed
    //                     addTo( resultsSum, breed.result);
    //                     addTo( sectionSum, breed.result);
    //                     addTo( subsectionSum, breed.result);
    //                 }
    //                 const breedSum = createTotal();
    //                 for( const color of breed.colors ) { // layers by color
    //                     if( color.result ) {
    //                         addTo( resultsSum, color.result);
    //                         addTo( sectionSum, color.result);
    //                         addTo( subsectionSum, color.result);
    //                         addTo( breedSum, color.result );
    //                     }
    //                 }
    //                 breed.total = avgTotal( breedSum );
    //             }
    //             subsection.total = avgTotal( subsectionSum );
    //         }
    //         section.total = avgTotal( sectionSum );
    //     }
    //     totalledReport.total = avgTotal( resultsSum );
    //     //totalledReport = report;
    // }

    function calcTotals() {
        let totalled = $state.snapshot( data ); // remove reactivity
        const resultsSum = createTotal(); // for total over all sections
        for( const section of totalled.sections ) {
            const sectionSum = createTotal(); // for total per section
            for( const subsection of section.subsections ) {
                const subsectionSum = createTotal(); // for total per subsection
                for( const breed of subsection.breeds ) {
                    if( breed.result ) { // pigeons by breed
                        addTo( resultsSum, breed.result);
                        addTo( sectionSum, breed.result);
                        addTo( subsectionSum, breed.result);
                    }
                    const breedSum = createTotal();
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
        totalled.total = avgTotal( resultsSum );
        return totalled;
    }

</script>

<!-- combi of table and div for better printing results -->

{#if data && totalled !== null && totalled.sections.length > 0 }
    {#key totalled }
        <div class='flex flex-col' in:fade>
            {#each totalled.sections as section, s}
                <!-- table per section for nice printing per page header-->
                <table class='w-full px-2 break-after-page'>
                    <!-- section header -->
                    <thead>
                        <tr>
                            <th class='screen:sticky screen:top-1' colspan=14>
                                <div class='flex flex-col'>
                                    <div class='flex flex-row p-1 rounded-b-none border-header bg-header text-header'>
                                        <small class='w-48 pr-2 text-left self-end'>{#if district} {district.short} {/if}</small>
                                        <div class='grow text-center text-xl italic'>Sparte {section.name}</div>
                                        <small class='w-48 pr-2 text-right self-end'>{#if district} {year} {/if}</small>
                                    </div>
                                    <div class='rounded-none flex flex-row bg-gray-300 px-2 text-center'>
                                        <div class='grow text-left'>Gruppe, Rasse & Farbe</div>
                                        <div class='w-12'>Zuchten</div>
                                        <div class='w-4'></div>
                                        <div class='w-40 text-center'> {#if section.id === 5}-{:else}Legeleistung{/if} </div>
                                        <div class='w-4'></div>
                                        <div class='w-48 text-center'>Brutleistung</div>
                                        <div class='w-4'></div>
                                        <div class='w-24 text-center'>Schauleistung</div>
                                        <div class='w-2'></div>
                                    </div>
                                    <div class='flex flex-row rounded-t-none border-b border-gray-600 bg-gray-300 px-2 text-xs text-center gap-x-0'>
                                        <div class='grow text-left'>Rasse & Farbe</div>
                                        <div class='w-12 th'>Zuchten</div>

                                        <div class='w-4 text-gray-400'>|</div>

                                        {#if section.id === 5}
                                            <div class='w-20 text-center'>-</div>
                                            <div class='w-20 text-center'>-</div>
                                        {:else}
                                            <div class='w-20 text-center'>Eier/Soll</div>
                                            <div class='w-20 text-center'>Gewicht/Soll</div>
                                        {/if}

                                        <div class='w-4 text-gray-400'>|</div>

                                        {#if section.id === 5}
                                            <div class='w-12 th'>Paare</div>
                                            <div class='w-12 th'>Bruten</div>
                                            <div class='w-12 th'>Schl %</div>
                                            <div class='w-12 th'>⍉/Pa</div>
                                        {:else}
                                            <div class='w-12 th'>Eier</div>
                                            <div class='w-12 th'>Befr %</div>
                                            <div class='w-12 th'>Schl %</div>
                                            <div class='w-12'>-</div>
                                        {/if}

                                        <div class='w-4 text-gray-400'>|</div>

                                        <div class='w-12 th'>Tiere</div>
                                        <div class='w-12 th'>Punkte</div>

                                        <div class='w-2'></div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </thead>

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
                                <td>
                                    <div class='flex flex-row border-b border-gray-600 bg-gray-300 px-2 text-xs text-center gap-x-0'>
                                        <div class='grow text-left'>Rasse & Farbe</div>
                                        <div class='w-12 th'>Zuchten</div>

                                        <div class='w-4 text-gray-400'>|</div>

                                        {#if section.id === 5}
                                            <div class='w-20 text-center'>-</div>
                                            <div class='w-20 text-center'>-</div>
                                        {:else}
                                            <div class='w-20 text-center' title='Anteil von Soll'>Legen/Soll</div>
                                            <div class='w-20 text-center' title='Anteil von Soll'>Gewicht/Soll</div>
                                        {/if}

                                        <div class='w-4 text-gray-400'>|</div>

                                        {#if section.id === 5}
                                            <div class='w-12 th'>Paare</div>
                                            <div class='w-12 th'>Bruten</div>
                                            <div class='w-12 th'>Schl %</div>
                                            <div class='w-12 th'>⍉/Pa</div>
                                        {:else}
                                            <div class='w-12 th' title='Gesamtzahl eingelegten Eier'>Eier</div>
                                            <div class='w-12 th' title='Anteil befruchteten Eier'>Befr %</div>
                                            <div class='w-12 th' title='Anteil geschlüpften Eier'>Schl %</div>
                                            <div class='w-12'>-</div>
                                        {/if}

                                        <div class='w-4 text-gray-400'>|</div>

                                        <div class='w-12 th'>Tiere</div>
                                        <div class='w-12 th'>Punkte</div>

                                        <div class='w-2'></div>
                                    </div>

                                </td>
                            </tr>

                        </tbody>

                        <!-- Breeds -->
                        {#each subsection.breeds as breed}
                            <tbody class='print-no-break'>
                                <tr>
                                    <th>
                                        <div class='flex flex-row px-2 py-1 text-right text-base font-semibold gap-x-0'>
                                            <div class='grow text-left'>
                                                {breed.name}
                                                {#if section.id === cfg.pigeons}
                                                    <span class='text-xs'>
                                                        (Gesamt)
                                                    </span>
                                                {/if}
                                            </div>

                                            {#if breed.result}
                                                <div class='w-12 td' title='Zahl der Zuchten / Züchter'>{dec( breed.result.breeders )}</div>
                                            {:else}
                                                <div class='w-12 td' title='Zahl der Zuchten / Züchter'>{dec( breed.total.breeders )}</div>
                                            {/if}

                                            <div class='w-4'></div>

                                            <!-- Lay-->
                                            {#if section.id === 5 && breed.result}
                                                <div class='w-20'></div>
                                                <div class='w-20'></div>
                                            {:else}
                                                <div class='w-20 text-center'
                                                     title={`Legen ${breed.total.layEggs * breed.layEggs} Eier von ${dec( breed.layEggs )} ergibt ${pct( breed.total.layEggs, 1 )} Eier im Jahr`}>
                                                    {#if breed.total.layEggs > 0}
                                                        {dec( breed.total.layEggs * breed.layEggs, 0 )}
                                                        /
                                                        {breed.layEggs}
                                                    {/if}
                                                    <!--{pct(breed.total.layEggs, 1 )}-->
                                                </div>
                                                <div class='w-20 text-center' title={`Eigewicht ${pct( breed.total.layWeight, 1 )} von ${dec( breed.layWeight )}g. ergibt ${dec( breed.total.layWeight * breed.layWeight )} g.`}>
                                                    {#if breed.total.layWeight > 0}
                                                        {dec(breed.total.layWeight * breed.layWeight)} / {breed.layWeight}
                                                    {/if}
                                                    <!--{pct( breed.total.layWeight, 1 )}-->
                                                </div>
                                            {/if}

                                            <div class='w-4'></div>
                                            <!-- Brood-->
                                            {#if section.id === 5 && breed.result} <!-- pigeons -->
                                                <div class='w-12 td' title='Zahl der Brutpaare'>{dec( breed.result.pairs )}</div> <!-- 2 eggs per brood -->
                                                <div class='w-12 td' title='Zahl der Bruten'>{dec( breed.result.broodPigeonEggs / 2 )}</div> <!-- 2 eggs per brood -->
                                                <div class='w-12 td' title='Anteil der geschlüpften Küken'>{ breed.result.broodPigeonEggs ? pct( breed.result.broodPigeonHatched ,1 ) : '' }</div>
                                                <div class='w-12 td' title='Zahl der Küken pro Paar'>{dec( breed.result.broodPigeonResult, 1 )}</div>
                                            {:else}
                                                <div class='w-12 td' title='Zahl der Eingelegte Eier'>{dec( breed.total.broodLayerEggs )}</div>
                                                <div class='w-12 td' title='Anteil befruchteten Eier'>{ breed.total.broodLayerEggs ? pct( breed.total.broodLayerFertile, 1 ) : '' }</div>
                                                <div class='w-12 td' title='Anteil geschlüpfte Küken'>{ breed.total.broodLayerEggs ? pct( breed.total.broodLayerHatched, 1 ) : ''}</div>
                                                <div class='w-12'></div>
                                            {/if}

                                            <div class='w-4'></div>
                                            <!-- Show -->
                                            {#if section.id === 5 && breed.result}
                                                <div class='w-12 td' title='Zahl der ausgestellten Tieren'>{dec( breed.result.showCount ) }</div>
                                                <div class='w-12 td' title='Durchschnitt Bewertungsnote'>{dec( breed.result.showScore, 1 )}</div>
                                            {:else}
                                                <div class='w-12 td' title='Zahl der ausgestellten Tieren'>{dec( breed.total.showCount ) }</div>
                                                <div class='w-12 td' title='Durchschnitt Bewertungsnote'>{dec( breed.total.showScore, 1 )}</div>
                                            {/if}

                                            <div class='w-2'></div>

                                        </div>
                                    </th>
                                </tr>


                                <!-- Colors, only for layers -->
                                {#each breed.colors as color}
                                    {#if section.id !== 5 && color.result}
                                        <tr>
                                            <td>
                                                <div class='flex flex-row px-2 py-1 text-right text-base gap-x-0'>
                                                    <div class='grow pl-4 text-left '> &#10551; {color.name || color.result.aocColor} </div>

                                                    <div class='w-12 td' title='Zahl der Zuchten / Züchter'>{dec( color.result.breeders )}</div>

                                                    <div class='w-4'></div>

                                                    <!-- Lay-->
                                                    <div class='w-20 text-center whitespace-nowrap' title='Relative Legeleistung im Jahr zu Soll'>
                                                        {#if color.result.layEggs > 0}
                                                            {dec( color.result.layEggs * breed.layEggs, 0 )} / {breed.layEggs}
                                                        {/if}

                                                        <!-- {pct(color.result.layEggs, 1 )} -->
                                                    </div>
                                                    <div class='w-20 text-center whitespace-nowrap' title='Relative Eiergewichtsleistung zu Soll'>
                                                        {#if color.result.layWeight > 0}
                                                            {dec( color.result.layWeight * breed.layWeight, 0 )} / {breed.layWeight}
                                                        {/if}
                                                        <!-- {pct( color.result.layWeight, 1 )} -->
                                                    </div>

                                                    <div class='w-4'></div>
                                                    <!-- Brood-->
                                                    <div class='w-12 td' title='Eingelegte Eier'>{dec( color.result.broodLayerEggs )}</div>
                                                    <div class='w-12 td' title='Anteil befruchteten Eier'>{pct( color.result.broodLayerFertile, 1 )}</div>
                                                    <div class='w-12 td' title='Anteil geschlüpfte Küken'>{pct( color.result.broodLayerHatched, 1 )}</div>
                                                    <div class='w-12'></div>

                                                    <div class='w-4'></div>

                                                    <div class='w-12 td' title='Zahl der ausgestellten Tieren'>{dec( color.result.showCount ) }</div>
                                                    <div class='w-12 td' title='Durchschnitt Bewertungsnote'>{dec( color.result.showScore, 1 )}</div>

                                                    <div class='w-2'></div>
                                                </div>
                                            </td>
                                        </tr>
                                    {/if}
                                {/each} <!-- color -->
                            </tbody>
                        {/each} <!-- breed -->

                        <!-- total subsection -->
                        <tbody>
                            <tr>
                                <td>
                                    <div class='flex flex-row border-y border-gray-600 bg-gray-300 px-2 text-right text-base italic gap-x-0'>
                                        <div class='grow text-left '>Gesamt {subsection.name} </div>

                                        <div class='w-12 td' title='Zahl der Zuchten / Züchter'>{dec( subsection.total.breeders )}</div>

                                        <div class='w-4'></div>

                                        <!-- Lay-->
                                        {#if section.id === 5 }
                                            <div class='w-20'></div>
                                            <div class='w-20'></div>
                                        {:else}
                                            <div class='w-20 text-center' title='Relative Legeleistung im Jahr zu Soll'>{pct( subsection.total.layEggs, 1 )}</div>
                                            <div class='w-20 text-center' title='Relative Eiergewichtsleistung im Jahr zu Soll'>{pct( subsection.total.layWeight, 1 )}</div>
                                        {/if}

                                        <div class='w-4'></div>
                                        <!-- Brood-->
                                        {#if section.id === 5 }
                                            <div class='w-12 td' title='Anzahl der Paare'>{dec( subsection.total.pairs ) }</div>
                                            <div class='w-12 td' title='Anzahl Bruten, jeder mit 2 Eier'>{subsection.total.broodPigeonEggs ? dec( subsection.total.broodPigeonEggs ) / 2 : '' }</div>
                                            <div class='w-12 td' title='Anteil geschlüpfte Küken'>{ subsection.total.broodPigeonHatched ? pct( subsection.total.broodPigeonHatched, 1 ) : '' }</div>
                                            <div class='w-12 td' title='Küken pro Paar'>{dec( subsection.total.broodPigeonResult, 1 )}</div>
                                        {:else}
                                            <div class='w-12 td' title='Eingelegte Eier'>{dec( subsection.total.broodLayerEggs )}</div>
                                            <div class='w-12 td' title='Anteil befruchteten Eier'>{ subsection.total.broodLayerEggs ? pct( subsection.total.broodLayerFertile, 1 ) : '' }</div>
                                            <div class='w-12 td' title='Anteil geschlüpfte Küken'>{ subsection.total.broodLayerEggs ? pct( subsection.total.broodLayerHatched, 1 ) : '' }</div>
                                            <div class='w-12'></div>
                                        {/if}

                                        <div class='w-4'></div>

                                        <div class='w-12 td' title='Zahl der ausgestellten Tieren'>{dec( subsection.total.showCount ) }</div>
                                        <div class='w-12 td' title='Durchschnitt Bewertungsnote'>{dec( subsection.total.showScore, 1 )}</div>

                                        <div class='w-2'></div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    {/each} <!-- subsection -->


                    <tbody>
                        <!-- section total -->
                        <tr>
                            <th>
                                <div class='flex flex-row border-y border-header bg-header text-header px-2 py-1 text-right text-base italic gap-x-0'>
                                    <div class='grow text-left '>Gesamt {section.name} </div>

                                    <div class='w-12 td' title='Zahl der Zuchten / Züchter'>{dec( section.total.breeders )}</div>

                                    <div class='w-4'></div>

                                    <!-- Lay-->
                                    {#if section.id === 5 }
                                        <div class='w-20 text-center'>-</div>
                                        <div class='w-20 text-center'>-</div>
                                    {:else}
                                        <div class='w-20 text-center' title='Durchschnitt Legeleistung im Jahr'>{pct( section.total.layEggs, 1 )}</div>
                                        <div class='w-20 text-center' title='Durchschnitt Eiergewicht'>{pct( section.total.layWeight, 1 )}</div>
                                    {/if}

                                    <div class='w-4'></div>
                                    <!-- Brood-->
                                    {#if section.id === 5 }
                                        <div class='w-12 td' title='Anzal Paare'>{dec( section.total.pairs )}</div>
                                        <div class='w-12 td' title='Anzahl Bruten, jeder mit 2 Eier'>{dec( section.total.broodPigeonEggs / 2 )}</div>
                                        <div class='w-12 td' title='Anteil befruchteten Eier'>{pct( section.total.broodPigeonHatched, 1 )}</div>
                                        <div class='w-12 td' title='Anteil geschlüpfte Küken'>{dec( section.total.broodPigeonResult, 1 )}</div>
                                    {:else}
                                        <div class='w-12 td' title='Eingelegte Eier'>{dec( section.total.broodLayerEggs )}</div>
                                        <div class='w-12 td' title='Anteil befruchteten Eier'>{ section.total.broodLayerEggs ? pct( section.total.broodLayerFertile, 1 ) : '-' }</div>
                                        <div class='w-12 td' title='Anteil geschlüpfte Küken'>{ section.total.broodLayerEggs ? pct( section.total.broodLayerHatched, 1 ) : '-' }</div>
                                        <div class='w-12'></div>
                                    {/if}

                                    <div class='w-4'></div>

                                    <div class='w-12 td' title='Zahl der ausgestellten Tieren'>{dec( section.total.showCount ) }</div>
                                    <div class='w-12 td' title='Durchschnitt Bewertungsnote'>{dec( section.total.showScore, 1 )}</div>

                                    <div class='w-2'></div>

                                </div>

                            </th>
                        </tr>
                        <!-- end section total -->
                    </tbody>


                </table>
                <div class=' text-center'> - </div>
            {/each}
            <table>
                <thead>
                    <!-- totals -->
                    <tr>
                        <th class='border-y border-gray-600 p-2 bg-header text-header text-center text-xl' colspan=14>
                            Gesammt Geflügel
                        </th>
                    </tr>
                    <tr>
                        <th>
                            <div class='flex flex-row bg-gray-300 px-2 gap-x-0 font-bold'>
                                <div class='grow text-left'>Alle Sparten, Gruppen, Rassen & Farben</div>
                                <div class='w-12 text-center'>Zuchten</div>
                                <div class='w-4'></div>
                                <div class='w-40'></div>
                                <div class='w-4'></div>
                                <div class='w-48'></div>
                                <div class='w-4'></div>
                                <div class='w-24 text-center'>Schauleistung</div>
                                <div class='w-2'></div>
                            </div>
                            <div class='flex flex-row bg-gray-300 px-2 text-xs'>
                                <div class='grow text-left'></div>
                                <div class='w-12'></div>
                                <div class='w-4'></div>
                                <div class='w-40'></div>
                                <div class='w-4'></div>
                                <div class='w-48'></div>
                                <div class='w-4'></div>
                                <div class='w-12 text-center'>Tiere</div>
                                <div class='w-12 text-center'>Punkte</div>
                                <div class='w-2'></div>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>
                            <div class='flex flex-row bg-header text-header px-2 gap-x-0 justify-evenly font-bold text-base italic border-y border-gray-600'>
                                <div class='grow'>Gesamt</div>
                                {#if totalled}
                                    <div class='w-12 text-right' title='Zahl der Zuchten / Züchter'>{ totalled.total.breeders}</div>
                                    <div class='w-4'></div>
                                    <div class='w-40'></div>
                                    <div class='w-4'></div>
                                    <div class='w-48'></div>
                                    <div class='w-4'></div>
                                    <div class='w-12 text-right' title='Zahl der ausgestellten Tieren'>{dec( totalled.total.showCount )}</div>
                                    <div class='w-12 text-right' title='Durchschnitt Bewertungsnote'>{dec( totalled.total.showScore, 1 )}</div>
                                    <div class='w-2'></div>
                                {/if}
                            </div>
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    {/key}
{:else}
    <h2 class='p-2 text-center text-xl'>Leider keine Daten für dieses Jahr</h2>
{/if}



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