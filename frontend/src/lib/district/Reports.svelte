<script>
    import { onMount } from 'svelte';
    import { dec, pct } from '../../js/util.js';

    import api from '../../js/api.js';
    import {meta} from 'tinro';

    import Reports from '../Reports.svelte';

    export let districtId;
    let district;
    let reports = [];

    const route = meta();

    function handle( breederId ) {
        api.district.reports.get( districtId ).then( data => {
            district = data.district;
            reports = data.reports;
            console.log( 'BreederReports', reports );
        })
    }

    onMount( () => {
    });

    $: handle( districtId )

</script>


{#if district}
    <h2 class='text-center' >Meldungen für Verband {district.name}</h2>

    <Reports reports={reports} />
{/if}



<style>

</style>