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
        result.broods = result.broodEggs ? result.broodEggs / 2 : null; // for pigeons
        result.broodResult = result.pairs && result.broodHatched ? result.broodHatched / result.pairs : null;

        total.breeders += result.breeders;
        total.pairs += result.pairs;
        total.layDames += result.layDames;

        if (result.layEggs) {
            total.layers += result.breeders;
            total.layShould += result.breeders * result.layShould; // needs div by total breeders
            total.layEggs += result.breeders * result.layEggs; // needs div by total breeders
        }

        if (result.layWeight) {
            total.layWeighters += result.breeders;
            total.layWeightShould += result.breeders * result.layWeightShould;
            total.layWeight += result.breeders * result.layWeight;
        }

        if (result.broodLayerEggs) {
            total.brooders += result.breeders;
            total.broodEggs += result.broodLayerEggs;
            total.broodFertile += result.breeders * result.broodLayerFertile;
            total.broodHatched += result.breeders * result.broodLayerHatched;
        }
        if( result.pairs && result.broodPigeonChicks ) {
            total.brooders += result.breeders;
            total.broodChicks += result.broodPigeonChicks;
            total.broodResult += result.breeders * result.broodPigeonProduction;
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
                let total = { breeders:0, pairs:0, layDames:0, layers:0, layShould:0, layEggs:0, layWeighters:0, layWeightShould:0, layWeight:0, brooders:0, broodEggs:0, broodFertile:0, broodHatched:0, broodChicks:0, broodResult:0, showers:0, showCount:0, showScore:0 };
                for( const subSection of section.subsections ) {
                    let subTotal = { breeders:0, pairs:0, layDames:0, layShould:0, layers:0, layEggs:0, layWeighters:0, layWeightShould:0, layWeight:0, brooders:0, broodEggs:0, broodFertile:0, broodHatched:0, broodChicks:0, broodResult:0, showers:0, showCount:0, showScore:0 };
                    for( const breed of subSection.breeds ) {
                        if( breed.result ) {
                            addTo( total, breed.result);
                            addTo( subTotal, breed.result);
                        }
                        for( const color of breed.colors ) {
                            if( color.result ) {
                                addTo( total, color.result);
                                addTo( subTotal, color.result);
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
                    subTotal.broodResult = subTotal.brooders ? subTotal.broodResult / subTotal.brooders : null; // avg of ch/pair per breeder.

                    // showCount
                    subTotal.showScore = subTotal.showers ? subTotal.showScore / subTotal.showers : null;
                    subSection.total = subTotal;
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
                total.broodResult = total.brooders ? total.broodResult / total.brooders : null; // avg of ch/pair per breeder.

                total.showScore = total.showers ? total.showScore / total.showers : null;
                section.total = total;
            }
        }
    }

    function onSection( id ) {
        return (event) => {
            router.location.query.set( 'section',id );
        }
    }

    function onBreed( sectionId, breedId ) {
        return (event) => {
            router.location.query.set( 'section', sectionId );
            router.location.query.set( 'breed', breedId );
        }
    }

    function onColor( sectionId, breedId, colorId ) {
        return (event) => {
            router.location.query.set( 'section', sectionId );
            router.location.query.set( 'breed', breedId );
            router.location.query.set( 'color', colorId );
        }
    }

    $: calcTotals( results );

</script>


<div class='print'>
    {#if results && results.length > 0 }
        {#each results.sections as section}
            <div>
                <!-- TOP HEADERS -->

                <h2 class='p-2 bg-header text-center text-white text-xl' on:click={onSection( section.id )} >
                    Sparte {section.name} XX
                </h2>
                <div class='flex flex-row px-2 gap-x-1 font-bold'>
                    <div class='w-64 text-center'>Gruppe / Rasse / Farbe</div>
                    <div class='grow flex flex-row justify-evenly'>
                        <div class='w-14 border-b text-center'>Zuchten</div>
                        <div class='w-40 border-b text-center text-center'> {#if section.id === 5}-{:else}Legeleistung{/if} </div>
                        <div class='w-40 border-b text-center'>Brutleistung</div>
                        <div class='w-28 border-b text-center'>Schauleistung</div>
                    </div>
                </div>

                {#each section.subsections as subsection}
                    <div>
                        <div class='sticky top-0'>
                            <div class='flex flex-row bg-gray-300 mt-4 px-2 gap-x-4 font-bold text-xl text-left' on:click={onSection( subsection.id )}>
                                {subsection.name}
                            </div>

                            <!-- Subsections header -->
                            <div class='flex flex-row bg-gray-300 px-2 gap-x-1 text-xs'>
                                <div class='w-64 text-left'>Rasse & Farbe</div>
                                <div class='grow flex flex-row justify-evenly'>
                                    <div class='flex w-14 justify-evenly'>
                                        <div class='td'>Zuchten</div>
                                    </div>
                                    <div class='flex w-40 justify-evenly'>
                                        {#if section.id === 5}
                                            <div class='td'>-</div> <div class='td'>-</div> <div class='td'>-</div>
                                        {:else}
                                            <div class='td'>Soll</div> <div class='td'>Eier/J</div> <div class='td'>Gewicht</div>
                                        {/if}
                                    </div>
                                    <div class='flex w-40 justify-evenly'>
                                        {#if section.id === 5}
                                            <div class='td'>Paare</div> <div class='td'>Küken</div> <div class='td'>Kü/Pa</div>
                                        {:else}
                                            <div class='td'>Eier</div> <div class='td'>Befr.</div> <div class='td'>Küken</div>
                                        {/if}
                                    </div>
                                    <div class='flex w-28 justify-evenly'>
                                        <div class='td'>Tiere</div>
                                        <div class='td'>Punkte</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BREEDS, RESULTS AND COLOR RESULTS -->
                        {#each subsection.breeds as breed, i}
                            <div class='flex flex-row mt-1 px-2 gap-x-1 bg-gray-100 print-no-break'>
                                <div class='w-64 text-left text-base font-semibold' on:click={onBreed( section.id, breed.id )}>
                                    {breed.name}
                                </div>
                                {#if section.id === 5 && breed.result}
                                    <div class='grow flex justify-evenly text-sm'>
                                        <div class='flex w-14 justify-evenly'>
                                            <div class='td' title='Zahl der Zuchten / Züchter'>{dec( breed.result.breeders )}</div>
                                        </div>
                                        <div class='flex w-40 justify-evenly'> </div>
                                        <div class='flex w-40 justify-evenly'>
                                            <div class='td'>{dec( breed.result.pairs )}</div>
                                            <div class='td' title='Geschlüpfte Küken'>{dec( breed.result.broodPigeonChicks ) }</div>
                                            <div class='td' title='Zahl der Küken pro Paar'>{dec( breed.result.broodPigeonProduction, 1 )}</div>
                                        </div>
                                        <div class='flex w-28 justify-evenly'>
                                            <div class='td' title='Zahl der ausgestellten Tieren'>{dec( breed.result.showCount > 0 ? breed.result.showCount : null )}</div>
                                            <div class='td' title='Durchschnitt Bewertungsnote'>{dec( breed.result.showScore, 1 )}</div>
                                        </div>
                                    </div>
                                {:else}
                                    <div class='grow'></div>
                                {/if}

                                {#each breed.colors as color}
                                    {#if section.id !== 5 && color.result}
                                        <div class='flex flex-row px-2 gap-x-1 print-no-break'>
                                            <div class='w-64 pl-4' on:click={onColor( section.id, breed.id, color.id )}>
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
                                    {/if}
                                {/each}
                            </div>

                        {/each}

                        <Comment>TOTAL PER SUBSECTION</Comment>

                        <div class='flex flex-row bg-gray-300 my-1 px-2 gap-x-1 justify-evenly text-sm italic'>
                            <div class='w-64'>Gesamt {subsection.name}</div>
                            <div class='grow flex justify-evenly'>
                                <div class='flex w-14 justify-evenly'>
                                    <div class='td' title='Zahl der Zuchten / Züchter'>{dec( subsection.total.breeders )}</div>
                                </div>
                                <div class='flex w-40 justify-evenly'>
                                    {#if section.id === 5 }
                                        <div class='td'>-</div> <div class='td'>-</div> <div class='td'>-</div>
                                    {:else}
                                        <div class='td' title='Soll Legeleistung im Jahr'>{dec( subsection.total.layShould )}</div>
                                        <div class='td' title='Durchschnitt Legeleistung im Jahr'>{pct(subsection.total.layEggs, 1, 1)}</div>
                                        <div class='td' title='Durchschnitt Eiergewicht'>{pct( subsection.total.layWeight, 1, 1 )}</div>
                                    {/if}
                                </div>
                                <div class='flex w-40 justify-evenly'>
                                    {#if section.id === 5 }
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
                                    <div class='td' title='Zahl der ausgestellten Tieren'>{dec( subsection.total.showCount )}</div>
                                    <div class='td' title='Durchschnitt Bewertungsnote'>{dec( subsection.total.showScore, 1 )}</div>
                                </div>
                            </div>
                        </div>

                    </div>
                {/each}


                <Comment>TOTAL PER SECTION</Comment>

                <div class='flex flex-row bg-gray-300 px-2 gap-x-1 justify-evenly font-bold text-sm italic border border-red'>
                    <div class='w-64'>Gesamt {section.name}</div>
                    <div class='grow flex justify-evenly text-sm'>
                        <div class='flex w-14 justify-evenly'>
                            <div class='td' title='Zahl der Zuchten / Züchter'>{dec( section.total.breeders )}</div>
                        </div>
                        <div class='flex w-40 justify-evenly'>
                            {#if section.id === 5 }
                                <div class='td'>-</div> <div class='td'>-</div> <div class='td'>-</div>
                            {:else}
                                <div class='td' title='Soll Legeleistung im Jahr'>{dec(section.total.layShould)}</div>
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
                            <div class='td' title='Zahl der ausgestellten Tieren'>{dec( section.total.showCount )}</div>
                            <div class='td' title='Durchschnitt Bewertungsnote'>{dec( section.total.showScore, 1 )}</div>
                        </div>
                    </div>
                </div>
                <div class='print-break text-center'> - </div>
            </div>
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