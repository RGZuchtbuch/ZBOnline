<script>
    import { onMount } from 'svelte';
    import {meta} from 'tinro';
    import { dec, pct, txt } from '../../js/util.js';
    import List from "../common/List.svelte";

    export let title = 'Meldungen';
    export let pairs = [];

    const route = meta();

    onMount( () => {
    });

</script>


<List>
    <h2 slot='title' class='text-center'>{title}</h2>

    <div slot='header'>
        <div class='flex flex-row bg-header p-2 gap-1 text-white'>
            <div class='w-28'>Meldung</div>
            <div class='w-104'>Rasse & Farbe</div>
            <div class='w-36'>Legen</div>
            <div class='w-36'>Bruten</div>
            <div class='w-24'>Schau</div>
        </div>

        <div class='flex flex-row bg-header gap-1 text-xs text-white'>
            <div class='w-28 flex flex-row px-2 gap-1'>
                <div class='w-10'>Jahr</div>
                <div class='w-12'>Code</div>
                <div class='w-4'>Grp</div>
            </div>
            <div class='w-104 flex flex-row px-2 gap-1'>
                <div class='w-56'>Rasse</div>
                <div class='w-48'>Farbe</div>
            </div>
            <div class='w-36 flex flex-row px-2 gap-1'>
                <div class='w-12'>Hennen</div>
                <div class='w-10'>Eier/J</div>
                <div class='w-14'>Gewicht</div>
            </div>
            <div class='w-36 flex flex-row px-2 gap-1'>
                <div class='w-12'>Eier</div>
                <div class='w-12'>Befr.</div>
                <div class='w-12'>Geschl.</div>
            </div>
            <div class='w-24 flex flex-row px-2 gap-1'>
                <div class='w-12'>Tiere</div>
                <div class='w-12'>Punkte</div>
            </div>
        </div>

    </div>

    <div slot='body'>
        {#each pairs as report}
            <a class='flex flex-row gap-1' href={route.match+'/'+report.id}>
                <div class='w-28 flex flex-row p-2 gap-1'>
                    <div class='w-10'>{report.year}</div>
                    <div class='w-12'>{report.name}</div>
                    <div class='w-4'>{report.group}</div>
                </div>
                <div class='w-104 flex flex-row p-2 gap-1'>
                    <div class='w-56'>{report.breedName}</div>
                    <div class='w-48'>{txt(report.colorName)}</div>
                </div>
                <div class='w-36 flex flex-row p-2 gap-1'>
                    <div class='w-12'></div>
                    <div class='w-10'></div>
                    <div class='w-14'></div>
                </div>
                <div class='w-36 flex flex-row p-2 gap-1'>
                    <div class='w-12'></div>
                    <div class='w-12'></div>
                    <div class='w-12'></div>
                </div>
                <div class='w-24 flex flex-row p-2 gap-1'>
                    <div class='w-12'></div>
                    <div class='w-12'></div>
                </div>
            </a>
        {/each}
    </div>

</List>



<style>

</style>