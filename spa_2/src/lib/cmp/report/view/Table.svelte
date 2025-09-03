<script>
    import {fade} from 'svelte/transition';
    import { cfg } from '$lib/js/store.svelte.js';
    import { dec, pct } from '$lib/js/tools.js';

    let { table, district, year } = $props();

    let totalledReport = null;

    calcTotals();
    // $effect( () => {
    //     if( table !== null ) {
    //         //calcTotals();
    //     }
    // });

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
    function calcTotals(  ) {
        totalledReport = table;
        const resultsSum = createTotal();
        for( const section of totalledReport.sections ) {
            const sectionSum = createTotal();
            for( const subsection of section.subsections ) {
                const subsectionSum = createTotal();
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
        totalledReport.total = avgTotal( resultsSum );
        //totalledReport = report;
    }

</script>

<!-- comby of table and div for better printing results -->

{#if totalledReport !== null && totalledReport.sections.length > 0 }
    {#key totalledReport }
        <div class='flex flex-col' in:fade>
            {#each totalledReport.sections as section, s}
                <!-- table per section for nice printing per page header-->
                <table class='w-full px-2 break-after-page'>
                    <!-- section header -->
                    <thead>
                        <tr>
                            <th class='sticky top-1' colspan=14>
                                <div class='flex flex-col'>
                                    <div class='flex flex-row p-1 rounded-b-none border-header bg-header text-header'>
                                        <small class='w-48 pr-2 text-left self-end'>{#if district} {district.short} {/if}</small>
                                        <div class='grow text-center text-xl italic'>Sparte {section.name}</div>
                                        <small class='w-48 pr-2 text-right self-end'>{#if district} {year} {/if}</small>
                                    </div>
                                    <div class='rounded-none flex flex-row bg-gray-300 px-2 text-center'>
                                        <div class='grow text-left'>Gruppe, Rasse & Farbe</div>
                                        <div class='w-1'></div>
                                        <div class='w-12'>Zuchten</div>
                                        <div class='w-8'></div>
                                        <div class='w-5'></div>
                                        <div class='w-24 text-center'> {#if section.id === 5}Legeleistung{:else}Legeleistung{/if} </div>
                                        <div class='w-8'></div>
                                        <div class='w-2'></div>

                                        <div class='w-48 text-center'>Brutleistung</div>
                                        <div class='w-8'></div>
                                        <div class='w-4'></div>

                                        <div class='w-24 text-center'>Schauleistung</div>
                                        <div class='w-4'></div>
                                    </div>
                                    <div class='flex flex-row rounded-t-none border-b border-gray-600 bg-gray-300 px-2 text-xs text-center gap-x-1'>
                                        <div class='grow text-left'>Rasse & Farbe</div>
                                        <div class='w-12 th'>Zuchten</div>

                                        <div class='w-8 text-gray-400'>|</div>

                                        {#if section.id === 5}
                                            <div class='w-12 th'>-</div>
                                            <div class='w-12 th'>-</div>
                                        {:else}
                                            <div class='w-12 th'>Eier/J</div>
                                            <div class='w-12 th'>Gewicht</div>
                                        {/if}

                                        <div class='w-8 text-gray-400'>|</div>

                                        {#if section.id === 5}
                                            <div class='w-12 th'>Paare</div>
                                            <div class='w-12 th'>Bruten</div>
                                            <div class='w-12 th'>Schl %</div>
                                            <div class='w-12 th'>Kü/Pa</div>
                                        {:else}
                                            <div class='w-12 th'>Eier</div>
                                            <div class='w-12 th'>Befr %</div>
                                            <div class='w-12 th'>Schl %</div>
                                            <div class='w-12'>-</div>
                                        {/if}

                                        <div class='w-8 text-gray-400'>|</div>

                                        <div class='w-12 th'>Tiere</div>
                                        <div class='w-12 th'>Punkte</div>

                                        <div class='w-2'></div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <!--tbody>
                        <tr>
                            <th>
                                <div class='flex flex-row bg-gray-300 px-2 text-center'>
                                    <div class='grow text-left'>Gruppe, Rasse & Farbe</div>
                                        <div class='w-1'></div>
                                    <div class='w-12'>Zuchten</div>
                                    <div class='w-8'></div>
                                        <div class='w-5'></div>
                                    <div class='w-24 text-center'> {#if section.id === 5}Legeleistung{:else}Legeleistung{/if} </div>
                                    <div class='w-8'></div>
                                        <div class='w-2'></div>

                                    <div class='w-48 text-center'>Brutleistung</div>
                                    <div class='w-8'></div>
                                        <div class='w-4'></div>

                                    <div class='w-24 text-center'>Schauleistung</div>
                                    <div class='w-4'></div>
                                </div>
                                <div class='flex flex-row border-b border-gray-600 bg-gray-300 px-2 text-xs text-center gap-x-1'>
                                    <div class='grow text-left'>Rasse & Farbe</div>
                                    <div class='w-12 th'>Zuchten</div>

                                    <div class='w-8 text-gray-400'>|</div>

                                    {#if section.id === 5}
                                        <div class='w-12 th'>-</div>
                                        <div class='w-12 th'>-</div>
                                    {:else}
                                        <div class='w-12 th'>Eier/J</div>
                                        <div class='w-12 th'>Gewicht</div>
                                    {/if}

                                    <div class='w-8 text-gray-400'>|</div>

                                    {#if section.id === 5}
                                        <div class='w-12 th'>Paare</div>
                                        <div class='w-12 th'>Bruten</div>
                                        <div class='w-12 th'>Schl %</div>
                                        <div class='w-12 th'>Kü/Pa</div>
                                    {:else}
                                        <div class='w-12 th'>Eier</div>
                                        <div class='w-12 th'>Befr %</div>
                                        <div class='w-12 th'>Schl %</div>
                                        <div class='w-12'>-</div>
                                    {/if}

                                    <div class='w-8 text-gray-400'>|</div>

                                    <div class='w-12 th'>Tiere</div>
                                    <div class='w-12 th'>Punkte</div>

                                    <div class='w-2'></div>
                                </div>
                            </th>
                        </tr>
                    </tbody-->

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
                                        <div class='flex flex-row border-b border-gray-600 bg-gray-300 px-2 text-xs text-center gap-x-1'>
                                            <div class='grow text-left'>Rasse & Farbe</div>
                                            <div class='w-12 th'>Zuchten</div>

                                            <div class='w-8 text-gray-400'>|</div>

                                            {#if section.id === 5}
                                                <div class='w-12 th'>-</div>
                                                <div class='w-12 th'>-</div>
                                            {:else}
                                                <div class='w-12 th' title='Anteil von Soll'>Legen</div>
                                                <div class='w-12 th' title='Anteil von Soll'>Gewicht</div>
                                            {/if}

                                            <div class='w-8 text-gray-400'>|</div>

                                            {#if section.id === 5}
                                                <div class='w-12 th'>Paare</div>
                                                <div class='w-12 th'>Bruten</div>
                                                <div class='w-12 th'>Schl %</div>
                                                <div class='w-12 th'>Kü/Pa</div>
                                            {:else}
                                                <div class='w-12 th' title='Gesamtzahl eingelegten Eier'>Eier</div>
                                                <div class='w-12 th' title='Anteil befruchteten Eier'>Befr %</div>
                                                <div class='w-12 th' title='Anteil geschlüpften Eier'>Schl %</div>
                                                <div class='w-12'>-</div>
                                            {/if}

                                            <div class='w-8 text-gray-400'>|</div>

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
                                            <div class='flex flex-row px-2 py-1 text-right text-base font-semibold gap-x-1'>
                                                <div class='grow text-left '>
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

                                                <div class='w-8'></div>

                                                <!-- Lay-->
                                                {#if section.id === 5 && breed.result}
                                                    <div class='w-12'></div>
                                                    <div class='w-12 td'></div>
                                                {:else}
                                                    <div class='w-12 td'
                                                         title={`Legen ${pct(breed.total.layEggs,1)} von ${dec( breed.layEggs )} ergibt ${dec( breed.total.layEggs * breed.layEggs )} Eier im Jahr`}>
                                                        {pct(breed.total.layEggs, 1 )}
                                                    </div>
                                                    <div class='w-12 td' title={`Eigewicht ${pct( breed.total.layWeight, 1 )} von ${dec( breed.layWeight )}g. ergibt ${dec( breed.total.layWeight * breed.layWeight )} g.`}>
                                                        {pct( breed.total.layWeight, 1 )}
                                                    </div>
                                                {/if}

                                                <div class='w-8'></div>
                                                <!-- Brood-->
                                                {#if section.id === 5 && breed.result} <!-- pigeons -->
                                                    <div class='w-12 td' title='Zahl der Brutpaare'>{dec( breed.result.pairs )}</div> <!-- 2 eggs per brood -->
                                                    <div class='w-12 td' title='Zahl der Bruten'>{dec( breed.result.broodPigeonEggs / 2 )}</div> <!-- 2 eggs per brood -->
                                                    <div class='w-12 td' title='Anteil der geschlüpften Küken'>{ breed.result.broodPigeonEggs ? pct( breed.result.broodPigeonHatched ,1 ) : '-' }</div>
                                                    <div class='w-12 td' title='Zahl der Küken pro Paar'>{dec( breed.result.broodPigeonResult, 1 )}</div>
                                                {:else}
                                                    <div class='w-12 td' title='Zahl der Eingelegte Eier'>{dec( breed.total.broodLayerEggs )}</div>
                                                    <div class='w-12 td' title='Anteil befruchteten Eier'>{ breed.total.broodLayerEggs ? pct( breed.total.broodLayerFertile, 1 ) : '-' }</div>
                                                    <div class='w-12 td' title='Anteil geschlüpfte Küken'>{ breed.total.broodLayerEggs ? pct( breed.total.broodLayerHatched, 1 ) : '-'}</div>
                                                    <div class='w-12'></div>
                                                {/if}

                                                <div class='w-8'></div>
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
                                                    <div class='flex flex-row px-2 py-1 text-right text-base gap-x-1'>
                                                        <div class='grow pl-4 text-left '> &#10551; {color.name || color.result.aocColor} </div>

                                                        <div class='w-12 td' title='Zahl der Zuchten / Züchter'>{dec( color.result.breeders )}</div>

                                                        <div class='w-8'></div>

                                                        <!-- Lay-->
                                                        <div class='w-12 td' title='Relative Legeleistung im Jahr zu Soll'>
                                                            {pct(color.result.layEggs, 1 )}
                                                        </div>
                                                        <div class='w-12 td' title='Relative Eiergewichtsleistung zu Soll'>
                                                            {pct( color.result.layWeight, 1 )}
                                                        </div>

                                                        <div class='w-8'></div>
                                                        <!-- Brood-->
                                                        <div class='w-12 td' title='Eingelegte Eier'>{dec( color.result.broodLayerEggs )}</div>
                                                        <div class='w-12 td' title='Anteil befruchteten Eier'>{pct( color.result.broodLayerFertile, 1 )}</div>
                                                        <div class='w-12 td' title='Anteil geschlüpfte Küken'>{pct( color.result.broodLayerHatched, 1 )}</div>
                                                        <div class='w-12'></div>

                                                        <div class='w-8'></div>

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
                                        <div class='flex flex-row border-y border-gray-600 bg-gray-300 px-2 text-right text-base italic gap-x-1'>
                                            <div class='grow pl-4 text-left '> Gesamt {subsection.name} </div>

                                            <div class='w-12 td' title='Zahl der Zuchten / Züchter'>{dec( subsection.total.breeders )}</div>

                                            <div class='w-8'></div>

                                            <!-- Lay-->
                                            {#if section.id === 5 }
                                                <div class='w-12'></div>
                                                <div class='w-12'></div>
                                            {:else}
                                                <div class='w-12 td' title='Relative Legeleistung im Jahr zu Soll'>{pct( subsection.total.layEggs, 1 )}</div>
                                                <div class='w-12 td' title='Relative Eiergewichtsleistung im Jahr zu Soll'>{pct( subsection.total.layWeight, 1 )}</div>
                                            {/if}

                                            <div class='w-8'></div>
                                            <!-- Brood-->
                                            {#if section.id === 5 }
                                                <div class='w-12 td' title='Anzahl der Paare'>{dec( subsection.total.pairs ) }</div>
                                                <div class='w-12 td' title='Anzahl Bruten, jeder mit 2 Eier'>{dec( subsection.total.broodPigeonEggs ) / 2 }</div>
                                                <div class='w-12 td' title='Anteil geschlüpfte Küken'>{pct( subsection.total.broodPigeonHatched, 1 )}</div>
                                                <div class='w-12 td' title='Küken pro Paar'>{dec( subsection.total.broodPigeonResult, 1 )}</div>
                                            {:else}
                                                <div class='w-12 td' title='Eingelegte Eier'>{dec( subsection.total.broodLayerEggs )}</div>
                                                <div class='w-12 td' title='Anteil befruchteten Eier'>{ subsection.total.broodLayerEggs ? pct( subsection.total.broodLayerFertile, 1 ) : '-' }</div>
                                                <div class='w-12 td' title='Anteil geschlüpfte Küken'>{ subsection.total.broodLayerEggs ?pct( subsection.total.broodLayerHatched, 1 ) : '-' }</div>
                                                <div class='w-12'></div>
                                            {/if}

                                            <div class='w-8'></div>

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
                                <div class='flex flex-row border-y border-header bg-header text-header px-2 py-1 text-right text-base italic gap-x-1'>
                                    <div class='grow pl-4 text-left '> Gesamt {section.name} </div>

                                    <div class='w-12 td' title='Zahl der Zuchten / Züchter'>{dec( section.total.breeders )}</div>

                                    <div class='w-8'></div>

                                    <!-- Lay-->
                                    {#if section.id === 5 }
                                        <div class='w-12'>-</div>
                                        <div class='w-12'>-</div>
                                    {:else}
                                        <div class='w-12 td' title='Durchschnitt Legeleistung im Jahr'>{pct( section.total.layEggs, 1 )}</div>
                                        <div class='w-12 td' title='Durchschnitt Eiergewicht'>{pct( section.total.layWeight, 1 )}</div>
                                    {/if}

                                    <div class='w-8'></div>
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

                                    <div class='w-8'></div>

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
                            <div class='flex flex-row bg-gray-300 px-2 gap-x-1 font-bold'>
                                <div class='grow text-left'>Alle Sparten, Gruppen, Rassen & Farben</div>
                                <div class='flex flex-row justify-evenly gap-x-6'>
                                    <div class='w-14 text-center'>Zuchten</div>
                                    <div class='w-28'></div>
                                    <div class='w-40'></div>
                                    <div class='w-12'></div>
                                    <div class='w-28 text-center'>Schauleistung</div>
                                </div>
                            </div>
                            <div class='flex flex-row bg-gray-300 px-2 gap-x-1 text-xs gap-x-1'>
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
                </thead>
                <tbody>
                    <tr>
                        <th>
                            <div class='flex flex-row bg-header text-white px-2 gap-x-1 justify-evenly font-bold text-base italic border-y border-gray-600'>
                                <div class='grow'>Gesamt</div>
                                <div class='flex justify-evenly text-base gap-x-6'>
                                    {#if table.total}
                                        <div class='flex w-14 justify-evenly'>
                                            <div class='td' title='Zahl der Zuchten / Züchter'>{table.total.breeders}</div>
                                        </div>
                                        <div class='w-28'></div>
                                        <div class='w-40'></div>
                                        <div class='w-12'></div>

                                        <div class='flex w-28 justify-evenly'>
                                            <div class='td' title='Zahl der ausgestellten Tieren'>{dec( table.total.showCount )}</div>
                                            <div class='td' title='Durchschnitt Bewertungsnote'>{dec( table.total.showScore, 1 )}</div>
                                        </div>
                                    {/if}
                                </div>
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