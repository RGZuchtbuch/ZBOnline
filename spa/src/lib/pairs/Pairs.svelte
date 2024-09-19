<script>
    import {getContext, onMount} from 'svelte';
    import {meta, router} from 'tinro';
    import { dec, pct, txt } from '../../js/util.js';
    import Page from "../common/Page.svelte";
    import {moderator, user} from '../../js/store.js';

    export let title = 'Meldungen';
    export let pairs = [];

    const district = getContext( 'district' );
    const breeder = getContext( 'breeder' );

    function addPair( event ) {
        console.log( 'Add pair', event );
        router.goto( './0' ); //http://localhost:8100/#/obmann/verband/6/zuechter/1/meldung/36 )
    }

    const route = meta();

    onMount( () => {
    });

    $:console.log( 'Breeder', $breeder );

</script>


<Page>
    <div slot='title' class='flex flex-row justify-between'>
        <div class='w-5'></div>
        <div class='text-center'> {title} </div>
        {#if $user && $user.moderator && $breeder }
            <a class='w-8 bg-button border-white border border-gray-400 rounded px-1 text-white' href={$router.path+'/0'} title='Meldung hinzufügen'> + </a>
        {:else }
            <div class='w-5' />
        {/if}

    </div>
    <div slot='header'>
        <div class='flex flex-row gap-1 text-center'>
            <div class='w-28'>Meldung</div>
            <div class='w-112'>Rasse & Farbe</div>
            <div class='w-32'>Legen</div>
            <div class='w-48'>Bruten</div>
            <div class='w-16'>Schau</div>
            <div class='grow'></div>


            <!--a class='border border-gray-400 p-1' href={$router.path+'/0'}> + </a-->
            <!--button type='button' on:click={addPair}> + </button-->

        </div>

        <div class='flex flex-row justify-evenly text-xs'>
            <div class='w-28 flex flex-row justify-evenly px-1'>
                <div class='w-10'>Jahr</div>
                <div class='w-12'>Code</div>
                <div class='w-4'>Grp</div>
            </div>
            <div class='w-16'>Züchter</div>
            <div class='w-104 flex flex-row justify-evenly'>
                <div class='w-56'>Rasse</div>
                <div class='w-40'>Farbe</div>
            </div>
            <div class='w-24 flex flex-row justify-evenly'>
                <div class='w-10'>Eier/J</div>
                <div class='w-10'>Gewicht</div>
            </div>
            <div class='w-32 flex flex-row justify-evenly'>
                <div class='w-10'>Befr.</div>
                <div class='w-10'>Geschl.</div>
                <div class='w-10'>Kü/Pa</div>
            </div>
            <div class='w-12 flex flex-row justify-evenly'>
                <div class='w-10'>Punkte</div>
            </div>
        </div>
    </div>

    <div slot='body'>
        {#each pairs as pair (pair.id) } <!-- ide gives error here, but it's correct svelte-->
            <a class='flex flex-row justify-evenly py-1 text-sm' href={route.match+'/'+pair.id}>
                <div class='w-28 flex flex-row justify-evenly'>
                    <div class='w-10'>{pair.year}</div>
                    <div class='w-12'>{pair.name}</div>
                    <div class='w-4 text-center'>{pair.group}</div>
                </div>
                <div class='w-16 whitespace-nowrap'>
                       {txt( pair.lastname )} {txt( pair.firstname, 1 )} {txt( pair.infix )}
                </div>
                <div class='w-104 flex flex-row justify-evenly'>
                    <div class='w-56'>{pair.breedName}</div>
                    <div class='w-40'>{txt(pair.colorName)}</div>
                </div>
                <div class='w-24 flex flex-row justify-evenly'>
                    <div class='w-10 text-right'>{dec( pair.layEggs, 0 )}</div>
                    <div class='w-10 text-right'>{dec( pair.layWeight, 1 )}</div>
                </div>
                <div class='w-34 flex flex-row justify-evenly'>
                    <div class='w-10 text-right'>{pct( pair.broodFertile, pair.broodEggs, 0 )}</div>
                    <div class='w-10 text-right'>{pct( pair.broodHatched, pair.broodEggs, 0 )}</div>
                    <div class='w-10 text-right'>{dec( pair.broodHatched, 1 )}</div>
                </div>
                <div class='w-12 flex flex-row justify-evenly'>
                    <div class='w-10 text-right'>{dec(pair.showScore, 1)}</div>
                </div>
            </a>
        {/each}
    </div>

</Page>



<style>

</style>