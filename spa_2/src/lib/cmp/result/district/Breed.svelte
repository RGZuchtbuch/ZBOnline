<script>
    import { page } from '$app/state';
    import { slide } from 'svelte/transition';
    import model from '$lib/js/model.js';
    import ColorResult from './ColorResult.svelte';
    import BreedResult from './BreedResult.svelte';

    let { breed, district, group, sectionId, year } = $props();

    let open = $state( breed.id === +page.url.searchParams.get( 'breed' ) );
    let results = $state( null ); // { breed, colors }

    let hasResults = $derived( breed.count > 0 ); // for css bold

    $effect( async () => {
        if( open ) {
            results = await model.Result.query( { district:district.id, year:year, group:group, breed:breed.id })
        }
    })

    async function onOpen() {
        open = ! open;
    }

    function onAddAoc() {
        const aocResult = {
            id:0, districtId:district.id, group:group, year:year,
            breeder:null, pairId:null,
            breeders:null, pairs:null,
            sectionId:sectionId, breedId:breed.id, colorId:null, aocColor:'AOC',
            lay:{ dames:null, eggs:null, weight:null },
            brood:{ chicks:null, eggs:null, fertile:null, hatched:null},
            show:{ count:null, score:null },
        }
        results.aocColors.push( aocResult )
    }

</script>

{#if breed}
    <div id={breed.id} class='flex flex-row my-1 p-1 bg-slate-100 bg-opacity-60 gap-x-1' title='Wähle zum Eingeben'>
        <div class='grow cursor-pointer ml-2 whitespace-nowrap' role='button' class:hasResults onclick={onOpen}>
            <span class='text-xl' title={breed.id}>{breed.name}</span>
        </div>

        {#if open }
            {#if breed.layer }
                <div class='flex flex-row py-1 gap-x-1 text-xs text-center items-end'>
                    <span class='w-14'>Zuchten</span>
                    <span class='w-14'></span>
                    <span class='w-4'></span>
                    <!-- div class='w-14'>Hennen</div --> <span class='w-14'>Eier/J</span> <span class='w-14 whitespace-nowrap'>Gewicht</span>
                    <span class='w-4'></span>
                    <span class='w-14'>Eingelegt</span> <span class='w-14'>Befruchtet</span> <span class='w-14'>Geschlüpft</span>
                    <span class='w-4'></span>
                    <span class='w-14'>Tiere</span> <span class='w-14 whitespace-nowrap'>Note</span>
                </div>
            {:else}
                <div class='flex flex-row gap-x-1 text-xs text-center items-end'>
                    <div class='w-14'>Zuchten</div> <div class='w-14'>Paare</div>
                    <div class='w-14'></div>
                    <div class='w-4'></div>
                    <div class='w-14'></div> <!-- div class='w-14'></div -->
                    <div class='w-4'></div>
                    <div class='w-14'>Bruten</div> <div class='w-14'>Küken</div> <div class='w-14'></div>

                    <div class='w-4'></div>
                    <div class='w-14'>Tiere</div> <div class='w-14 whitespace-nowrap'>Note</div>
                </div>
            {/if}
        {/if}
        <span class='w-4'></span>
    </div>

    {#if open && results && results.colors }
        <div transition:slide>
            {#if breed.layer }
                {#each results.colors as colorResult}
                    <ColorResult result={colorResult}/>
                {/each}
                {#each results.aocColors as colorResult}
                    <ColorResult result={colorResult}/>
                {/each}
            {:else}
                <BreedResult bind:result={results.breed}/>
            {/if}

            {#if sectionId !== 5}
                <div class='flex flex-row justify-end'>
                    <button class='border-button bg-button text-button' onclick={onAddAoc}>AOC</button>
                </div>
            {/if}
        </div>

    {/if}


{/if}

<style>
    .hasResults {
        @apply font-bold;
    }
</style>