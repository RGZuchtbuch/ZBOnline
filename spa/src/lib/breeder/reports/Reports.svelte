<script>
    import { createEventDispatcher } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../../js/api.js';
    import { user } from '../../../js/store.js'

    import DistrictRow from './ReportsRow.svelte';
    import ReportsRow from "./ReportsRow.svelte";

    export let breeder = null;

    let reports = null;

    const dispatch = createEventDispatcher();
    const route = meta();


    function loadReports( id ) {
        if( id ) {
            //api.breeder.reports.get(breeder.id).then(response => {
            //    reports = response.reports;
            //    console.log("Reports loaded", reports);
            //});
            console.log( 'Todo: loadReports');
        }
    }

    // forward district select to parent
    function onSelect( event ) {
        dispatch( 'select', event.detail );
    }

    $: loadReports( breeder.id ); // if user changes by logout/login or exp

</script>


{#if breeder}
    <h2 class='w-256 border border-gray-400 rounded-t p-2 bg-header text-center text-xl print'>Meldungen von Züchter {breeder.lastName} </h2>
    {#if reports }
        <div class='w-256 bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 px-4 scrollbar'>
            {#each reports as report}
                <ReportsRow report={report} />
            {/each}
        </div>
    {:else}
        Einen moment bitte
    {/if}
{/if}


<style>

</style>



