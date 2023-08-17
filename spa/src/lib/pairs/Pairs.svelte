<script>
    import { onMount } from 'svelte';
    import {meta} from 'tinro';
    import { dec, pct } from '../../js/util.js';
    import List from "../common/List.svelte";

    export let title = 'Meldungen';
    export let reports = [];

    const route = meta();

    onMount( () => {
    });

    console.log( 'Reports', reports );
</script>


<List>
    <h2 slot='title' class='text-center'>{title}</h2>

    <div slot='header'>
        <div class='flex flex-row bg-header p-2 gap-1 text-white'>
            <div class='w-40'>Meldung</div>
            <div class='w-80'>Rasse & Farbe</div>
            <div class='w-36'>Legen</div>
            <div class='w-48'>Bruten</div>
            <div class='w-32'>Schau</div>
        </div>

        <div class='flex flex-row bg-header gap-1 text-xs text-white'>
            <div class='w-40 flex flex-row px-2 gap-1'>
                <div class='w-16'>Jahr</div>
                <div class='w-16'>Stamm</div>
                <div class='w-8'>Grp</div>
            </div>
            <div class='w-80 flex flex-row px-2 gap-1'>
                <div class='w-48'>Rasse</div>
                <div class='w-32'>Farbe</div>
            </div>
            <div class='w-36 flex flex-row px-2 gap-1'>
                <div class='w-12'>Hennen</div>
                <div class='w-10'>Eier/J</div>
                <div class='w-14'>Gewicht</div>
            </div>
            <div class='w-48 flex flex-row px-2 gap-1'>
                <div class='w-16'>Eingelegt</div>
                <div class='w-16'>Befruchtet</div>
                <div class='w-16'>Geschüpft</div>
            </div>
            <div class='w-32 flex flex-row px-2 gap-1'>
                <div class='w-16'>Tiere</div>
                <div class='w-16'>Punkte</div>
            </div>
        </div>

    </div>

    <div slot='body'>
        {#each reports as report}
            <a class='flex flex-row gap-1' href={route.match+'/'+report.id}>
                <div class='w-40 flex flex-row p-2 gap-1'>
                    <div class='w-16'>{report.year}</div>
                    <div class='w-16'>{report.name}</div>
                    <div class='w-8'>{report.group}</div>
                </div>
                <div class='w-80 flex flex-row p-2 gap-1'>
                    <div class='w-48'>{report.breedId}</div>
                    <div class='w-32'>{report.colorId}</div>
                </div>
                <div class='w-36 flex flex-row p-2 gap-1'>
                    <div class='w-12'></div>
                    <div class='w-10'></div>
                    <div class='w-14'></div>
                </div>
                <div class='w-48 flex flex-row p-2 gap-1'>
                    <div class='w-16'></div>
                    <div class='w-16'></div>
                    <div class='w-16'></div>
                </div>
                <div class='w-32 flex flex-row p-2 gap-1'>
                    <div class='w-16'></div>
                    <div class='w-14'></div>
                </div>
            </a>
        {/each}
    </div>

</List>



<style>

</style>