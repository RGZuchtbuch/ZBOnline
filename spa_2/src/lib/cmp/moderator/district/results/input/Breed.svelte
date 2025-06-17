<script>
    import { slide } from 'svelte/transition';
    import { Result } from '$lib/js/Result.svelte.js';
    import ColorResult from './ColorResult.svelte';
    import BreedResult from './BreedResult.svelte';

//    let { breed, district, group, section, title, year, map } = $props();
    let { breed, district, group, year } = $props();

    let results = $state( null );

    let open = $state( false );

    let hasResults = $derived( breed.count > 0 );

    async function onOpen() {
        open = ! open;
        if( open && ! results ) {
            results = await Result.query( { breed:breed.id, district:district.id, group:group, year:year})
        }
    }

    function onResultChange( event ) { // from ResultRows
        // recount breeders when results change
        // let breeders = breed.reports + breed.aoc; // to get total breeders
        // for (let result of results) {
        //     if (result.breeders && result.breeders > 0) {
        //         breeders += result.breeders;
        //     }
        // }
        // breed.breeders = breeders;
    }

    // function newResult( colorId = null ) {
    //     return {
    //         id:0, districtId:district.id, group:group, year:year,
    //         breeder:null, pairId:null,
    //         breeders:null, pairs:null,
    //         sectionId:section.id, breedId:breed.id, colorId:colorId, aocColor:null,
    //         lay:{ dames:null, eggs:null, weight:null },
    //         brood:{ chicks:null, eggs:null, fertile:null, hatched:null},
    //         show:{ count:null, score:null },
    //     }
    // }


</script>

{#if breed}

    <div class='flex flex-row px-6 py-1 gap-x-1' title='Wähle zum Eingeben' transition:slide>
        <div class='w-80 cursor-pointer whitespace-nowrap' class:hasResults on:click={onOpen}>
            <span class=''>{breed.name}</span> <span>({breed.count})</span>
        </div>

        <div class='w-4'></div>

        {#if open }
            {#if breed.layer }
                <div class='flex flex-row gap-x-1 text-xs text-center'>
                    <div class='w-14'>Zuchten</div> <div class='w-14'></div>
                    <div class='w-2'></div>
                    <!-- div class='w-14'>Hennen</div --> <div class='w-14'>Eier/J</div> <div class='w-14 whitespace-nowrap'>Gewicht</div>
                    <div class='w-2'></div>
                    <div class='w-14'>Eingelegt</div> <div class='w-14'>Befruchtet</div> <div class='w-14'>Geschlüpft</div>
                    <div class='w-2'></div>
                    <div class='w-14'>Tiere</div> <div class='w-14 whitespace-nowrap'>Note</div>
                </div>
            {:else}
                <div class='flex flex-row gap-x-1 text-xs text-center'>
                    <div class='w-14'>Zuchten</div> <div class='w-14'>Paare</div>
                    <div class='w-2'></div>
                    <div class='w-14'></div>
                    <div class='w-14'></div> <!-- div class='w-14'></div -->
                    <div class='w-2'></div>
                    <div class='w-14'>Bruten</div>
                    <div class='w-14'>Küken</div>
                    <div class='w-14'></div>
                    <div class='w-2'></div>
                    <div class='w-14'>Tiere</div> <div class='w-14 whitespace-nowrap'>Note</div>
                </div>
            {/if}
            <div class='w-4'></div>
        {/if}
    </div>

    {#if open && results }
        <div transition:slide>
            {#if breed.layer }
                {#each results.colors as color}
                    <ColorResult result={color} onchange={onResultChange}/>
                {/each}
            {:else}
                <BreedResult result={results.breed} onchange={onResultChange}/>
            {/if}
        </div>
    {/if}


{/if}

<style>
    .hasResults {
        @apply font-bold;
    }
</style>