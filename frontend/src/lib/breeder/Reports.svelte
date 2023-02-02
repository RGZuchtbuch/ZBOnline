<script>
    import { onMount } from 'svelte';
    import { dec, pct } from '../../js/util.js';

    import api from '../../js/api.js';
    import {meta} from 'tinro';

    import Reports from '../Reports.svelte';

    export let breederId;
    let breeder;
    let reports = [];

    const route = meta();

    function handle( breederId ) {
        api.breeder.reports.get( breederId ).then( data => {
            breeder = data.breeder;
            reports = data.reports;
            console.log( 'BreederReports', reports );
        })
    }

    onMount( () => {
    });

    $: handle( breederId )

</script>


{#if breeder}
    <h2 class='text-center' >Meldungen für Züchter {breeder.name}</h2>

    <Reports reports={reports} />
{/if}



<style>

</style>