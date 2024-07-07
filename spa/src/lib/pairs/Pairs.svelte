<script>
    import { onMount } from 'svelte';
    import {meta} from 'tinro';
    import { dec, pct, txt } from '../../js/util.js';
    import Page from "../common/Page.svelte";

    export let title = 'Meldungen';
    export let pairs = [];

    const route = meta();

    onMount( () => {
    });

</script>


<Page>
    <h2 slot='title' class='text-center'>{title}</h2>

    <div slot='header'>
        <div class='flex flex-row bg-header gap-1 text-white text-center'>
            <div class='w-28'>Meldung</div>
            <div class='w-112'>Rasse & Farbe</div>
            <div class='w-32'>Legen</div>
            <div class='w-48'>Bruten</div>
            <div class='w-16'>Schau</div>
        </div>

        <div class='flex flex-row bg-header gap-1 text-xs text-white text-center'>
            <div class='w-28 flex flex-row px-2 gap-1 border-l'>
                <div class='w-10'>Jahr</div>
                <div class='w-12'>Code</div>
                <div class='w-4'>Grp</div>

            </div>
            <div class='w-112 flex flex-row px-2 gap-1 border-l'>
                <div class='w-64'>Rasse</div>
                <div class='w-48'>Farbe</div>
            </div>
            <div class='w-32 flex flex-row px-2 gap-1 border-l'>
                <div class='w-14'>Eier/J</div>
                <div class='w-14'>Gewicht</div>
            </div>
            <div class='w-48 flex flex-row px-2 gap-1 border-l'>
                <div class='w-14'>Befr.</div>
                <div class='w-14'>Geschl.</div>
                <div class='w-14'>Kü/Pa</div>
            </div>
            <div class='w-16 flex flex-row px-2 gap-1 border-x'>
                <div class='w-14'>Punkte</div>
            </div>
        </div>
    </div>

    <div slot='body'>
        {#each pairs as pair (pair.id) }
            <a class='flex flex-row gap-1' href={route.match+'/'+pair.id}>
                <div class='w-28 flex flex-row p-2 gap-1'>
                    <div class='w-10'>{pair.year}</div>
                    <div class='w-12'>{pair.name}</div>
                    <div class='w-4'>{pair.group}</div>
                </div>
                <div class='w-112 flex flex-row p-2 gap-1'>
                    <div class='w-64'>{pair.breedName}</div>
                    <div class='w-48'>{txt(pair.colorName)}</div>
                </div>
                <div class='w-32 flex flex-row p-2 gap-1'>
                    <div class='w-14 text-right'>{dec( pair.layEggs, 0 )}</div>
                    <div class='w-14 text-right'>{dec( pair.layWeight, 1 )}</div>
                </div>
                <div class='w-48 flex flex-row p-2 gap-1'>
                    <div class='w-14 text-right'>{pct( pair.broodFertile, pair.broodEggs, 0 )}</div>
                    <div class='w-14 text-right'>{pct( pair.broodHatched, pair.broodEggs, 0 )}</div>
                    <div class='w-14 text-right'>{dec( pair.broodHatched, 1 )}</div>
                </div>
                <div class='w-16 flex flex-row p-2 gap-1'>
                    <div class='w-14 text-right'>{dec(pair.showScore, 1)}</div>
                </div>
            </a>
        {/each}
    </div>

</Page>



<style>

</style>