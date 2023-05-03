<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'
    import ResultList from './ResultsList.svelte';

    export let districtId = null;
    export let year = null;
//    export let moderator = null;

    let district = null;
    let results = null;


    function handle( districtId, year ) {
        if( districtId ) {
            api.district.get( districtId ).then( response => {
                district = response.district;
            })
            api.district.results.get( districtId, year ).then( response => {
                results = response.results;
            })
        }
    }

    const route = meta();

    onMount( () => {
    })

    $: handle( districtId, year );

</script>

{#if district && year }
    <h2 class='text-center'>Verband {#if district} {district.name} {/if} → Leistungen {year}</h2>
    <ResultList {results} />
{/if}


<style>

</style>