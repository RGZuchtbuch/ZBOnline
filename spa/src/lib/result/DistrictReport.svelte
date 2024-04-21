<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'
    import Report from './Report.svelte';

    export let districtId = null;
    export let year = null;
//    export let moderator = null;

    let district = null;
    let report = null;


    function handle( districtId, year ) {
        if( districtId ) {
            api.district.get( districtId ).then( response => {
                district = response.district;
            })
            api.district.report.get( districtId, year ).then( response => {
                report = response.report;
            })
        }
    }

    const route = meta();

    onMount( () => {
    })

    $: handle( districtId, year );

</script>

<div class='flex flex-col my-2 border border-gray-400'>
{#if district && year }
    <h2 class='text-center'>Verband {#if district} {district.name} {/if} → Leistungsdaten {year}</h2>
    {#if report}
        <Report {district} {report} />
    {/if}
{/if}
</div>


<style>

</style>