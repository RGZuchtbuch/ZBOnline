<script>
    import { onMount } from 'svelte';
    import { dec, perc } from '../../js/util.js';

    import api from '../../js/api.js';
    import {meta} from 'tinro';

    import Results from '../Results.svelte';

    export let districtId;
    let district;
    let results = [];

    const route = meta();

    function handle( districtId ) {
        api.district.results.get( districtId ).then( data => {
            district = data.district;
            results = data.reports;
            console.log( 'BreederReports', results );
        })
    }

    onMount( () => {
    });

    $: handle( districtId )

</script>


{#if district}
    <h2 class='text-center' >Meldungen für Verband {district.name}</h2>
    <Results reports={results} />
{/if}



<style>

</style>