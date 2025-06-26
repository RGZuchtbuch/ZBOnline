<script>
    import { slide } from 'svelte/transition';
    import { Result } from '$lib/js/Result.svelte.js';
    import ColorResult from './ColorResult.svelte';
    import BreedResult from './BreedResult.svelte';

//    let { breed, district, group, section, title, year, map } = $props();
    let { data, breed } = $props();
    let open = $state( false );
    let breedResults = $state( null ); // { breed, colors }

    $effect( () => {
        if( data ) open = false; // close when data changes
    })

    $effect( async () => {
        if( open ) {
            console.log( 'Year', data.year );
            breedResults = null;
            breedResults = await Result.query( { district:data.district.id, year:data.year, group:data.group, breed:breed.id })
        }
    })
    let hasResults = $derived( breed.count > 0 );

    async function onOpen() {
        // if( ! open ) {
        //     console.log( 'Rs', results );
        // }
        open = ! open;
    }

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

    {#if open && breedResults }
        <div transition:slide>
            {#if breed.layer }
                {#each breedResults.colors as color}
                    <ColorResult result={color} {data}/>
                {/each}
            {:else}
                <BreedResult result={breedResults.breed} {data}/>
            {/if}
        </div>
    {/if}


{/if}

<style>
    .hasResults {
        @apply font-bold;
    }
</style>