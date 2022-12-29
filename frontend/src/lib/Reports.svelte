<script>
    import { onMount } from 'svelte';
    import {meta} from 'tinro';
    import { dec, perc } from '../js/util.js';

    export let reports = [];

    const route = meta();


    onMount( () => {
    });
    console.log( 'Reports', reports );
</script>


<div class='flex flex-row border border-gray-600 border-b-gray-400 rounded-t gap-x-1 text-xs bg-gray-200 px-2'>
    <div class='w-32 px-1'>Meeeldung</div>
    <div class='w-0'></div>
    <div class='w-0'></div>
    <div class='w-80 border border-l-gray-400 px-1'>Standard</div>
    <div class='w-0'></div>
    <div class='w-36 border border-l-gray-400 px-1'>Legen</div>
    <div class='w-0'></div>
    <div class='w-0'></div>
    <div class='w-40 border border-l-gray-400 px-1'>Bruten</div>
    <div class='w-0'></div>
    <div class='w-0'></div>
    <div class='w-24 border border-l-gray-400 px-1'>Schauen</div>
    <div class='w-0'></div>
</div>
<div class='flex flex-row border border-x-gray-600 gap-x-1 text-xs bg-gray-100 px-2'>
    <div class='w-8 px-1'>Jahr</div>
    <div class='w-8 border border-l-gray-400 px-1'>Grp</div>
    <div class='w-16 border border-l-gray-400 px-1'>Stamm</div>
    <div class='w-48 border border-l-gray-400 px-1'>Rasse</div>
    <div class='w-32 border border-l-gray-400 px-1'>Farbe</div>
    <div class='w-12 border border-l-gray-400 px-1'>Hennen</div>
    <div class='w-10 border border-l-gray-400 px-1'>Eier/J</div>
    <div class='w-14 border border-l-gray-400 px-1'>Gewicht</div>
    <div class='w-12 border border-l-gray-400 px-1'>Eingelegt</div>
    <div class='w-14 border border-l-gray-400 px-1'>Befruchtet</div>
    <div class='w-14 border border-l-gray-400 px-1'>Geschüpft</div>
    <div class='w-10 border border-l-gray-400 px-1'>Tiere</div>
    <div class='w-14 border border-l-gray-400 px-1'>Punkte</div>
    <a class='w-8 border border-x-gray-400 px-1' href={route.match+'/0'}>+</a>
</div>

<div class='bg-gray-100 overflow-y-scroll border border-gray-600 border-t-gray-400 rounded-b scrollbar px-2'>
    {#each reports as report}
        <a class='flex flex-row gap-x-1 text-xs' href={ route.match+'/'+report.reportId}>
            <div class='w-8'>{report.year}</div>
            <div class='w-8 border border-l-gray-400 px-1'>{report.group}</div>

            <div class='w-16 border border-l-gray-400 px-1'>{report.reportName}</div>

            <div class='w-48 border border-l-gray-400 px-1'>{report.breedName}</div>
            <div class='w-32 border border-l-gray-400 px-1'>{report.colorName}</div>

            <div class='w-12 border border-l-gray-400 px-1'>{dec(report.layDames)}</div>
            <div class='w-10 border border-l-gray-400 px-1'>{dec(report.layEggs)}</div>
            <div class='w-14 border border-l-gray-400 px-1'>{dec(report.layWeight)}</div>

            <div class='w-12 border border-l-gray-400 px-1'>{dec(report.broodEggs)}</div>
            <div class='w-14 border border-l-gray-400 px-1'>{perc( report.broodFertile, report.broodEggs )}</div>
            <div class='w-14 border border-l-gray-400 px-1'>{perc( report.broodHatched, report.broodEggs )}</div>

            <div class='w-10 border border-l-gray-400 px-1'>{dec(report.showCount)}</div>
            <div class='w-14 border border-l-gray-400 px-1'>{dec(report.showScore)}</div>
        </a>
    {/each}
</div>




<style>

</style>