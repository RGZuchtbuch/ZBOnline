<script>
    import { slide } from 'svelte/transition';

    import api        from '../../../js/api.js';
    import dic        from '../../../js/dictionairy.js';
    import ResultRow  from './ResultRow.svelte';

    export let districtId = null;
    export let sectionId = null;
    export let breed = null;
    export let year = null;
    export let group = null;
    export let title = null;

    export let open = false;

    let results = [];
    let hasResults = 0;

    function onOpen() {
        if( open ) {
            open = false;
        } else {
            if( results.length === 0 ) { // only if not already fetched
                api.district.results.breed.get( districtId, sectionId, breed.id, year, group ).then( response => {
                    results = response.results;
                    console.log( 'Results', results );
                })
            } else {
                results = results;
            }
            open = true;
        }
        console.log( 'Open', open );

    }

    function onResultChange( event ) { // from ResultRows
        // recount breeders when results change
        let breeders = breed.reports + breed.aoc; // to get total breeders
        for (let result of results) {
            if (result.breeders && result.breeders > 0) {
                breeders += result.breeders;
            }
        }
        breed.breeders = breeders;
    }

    $: hasResults = breed.results; // indicator for delete on save (NEEDED? )

</script>



<div class='flex flex-row px-2 gap-x-1'>
    <div class='w-80 cursor-pointer whitespace-nowrap' class:hasResults on:click={onOpen} {title}>
        {breed.name}
        {#if breed.results }
            <span class='text-xs'>
                (
                <span title={'Zuchten hier'}>{breed.breeders-breed.reports-breed.aoc}</span> +
                <span title={'Paar/Stamm Meldungen Züchter'}>{breed.reports}</span> +
                <span title={'Zuchten AOC Klasse'}>{breed.aoc}</span>
                )
            </span> {/if}
    </div>

    <div class='w-4'></div>

    {#if open }
        {#if sectionId === PIGEONS }
            <div class='flex flex-row gap-x-1 text-xs text-center'>
                <div class='w-14'>Zuchten</div> <div class='w-14'>Paare</div>
                <div class='w-2'></div>
                <div class='w-14'></div> <div class='w-14'></div> <!-- div class='w-14'></div -->
                <div class='w-2'></div>
                <div class='w-14'></div> <div class='w-14'>Küken</div> <div class='w-14'></div>
                <div class='w-2'></div>
                <div class='w-14'>Tiere</div> <div class='w-14 whitespace-nowrap'>Note</div>
            </div>
        {:else}
            <div class='flex flex-row gap-x-1 text-xs text-center'>
                <div class='w-14'>Zuchten</div> <div class='w-14'></div>
                <div class='w-2'></div>
                <!-- div class='w-14'>Hennen</div --> <div class='w-14'>Eier/J</div> <div class='w-14 whitespace-nowrap'>Gewicht</div>
                <div class='w-2'></div>
                <div class='w-14'>Eingelegt</div> <div class='w-14'>Befruchtet</div> <div class='w-14'>Geschlüpft</div>
                <div class='w-2'></div>
                <div class='w-14'>Tiere</div> <div class='w-14 whitespace-nowrap'>Note</div>
            </div>
        {/if}
        <div class='w-4'></div>
    {/if}
</div>


{#if open }
    {#each results as result}
        <ResultRow {sectionId} {result} on:change={onResultChange}/>
    {/each}
{/if}

<style>
    .hasResults {
        @apply font-bold;
    }
</style>