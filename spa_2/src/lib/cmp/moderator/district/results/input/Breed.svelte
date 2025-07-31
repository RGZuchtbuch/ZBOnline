<script>
    import { slide } from 'svelte/transition';
    import model from '$lib/js/model.js';
    import ColorResult from './ColorResult.svelte';
    import BreedResult from './BreedResult.svelte';

//    let { breed, district, group, section, title, year, map } = $props();
    let { breed, district, group, sectionId, year } = $props();

    //console.log( 'Prop section', sectionId )

    let open = $state( false );
    let results = $state( null ); // { breed, colors }

    $effect( () => {
        if( breed ) open = false; // close when data changes
    })

    $effect( async () => {
        if( open ) {
            results = null;
            results = await model.Result.query( { district:district.id, year:year, group:group, breed:breed.id })
        }
    })
    let hasResults = $derived( breed.count > 0 );

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
        results.colors.push( aocResult )
    }

</script>

{#if breed}

    <div class='flex flex-row px-6 py-1 gap-x-1' title='Wähle zum Eingeben' transition:slide>
        <div class='w-80 cursor-pointer whitespace-nowrap' class:hasResults onclick={onOpen}>
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
                    <ColorResult result={color}/>
                {/each}
                {#each results.aocColors as color}
                    <ColorResult result={color}/>
                {/each}
            {:else}
                <BreedResult result={results.breed}/>
            {/if}
        </div>
        {#if sectionId !== 5}
            <div class='flex flex-row justify-end' onclick={onAddAoc}><a>[AOC]</a></div>
        {/if}
    {/if}


{/if}

<style>
    .hasResults {
        @apply font-bold;
    }
</style>