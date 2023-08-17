<script>
    import { onMount } from 'svelte';
    import {dec, pct, txt} from '../../js/util.js';

    import api from '../../js/api.js';
    import {meta} from 'tinro';

    import Reports from '../pairs/Pairs.svelte';
    import List from "../common/List.svelte";

    export let breederId;
    let breeder;
    let reports;

    const route = meta();

    function loadReports( breederId ) {
        console.log( 'load reports for breeder' );
        api.breeder.pairs.get( breederId ).then(response => {
            breeder = response.breeder;
            reports = response.reports;
            console.log( 'BreederReports', reports );
        })
    }

    function update( breederId ) {
        breeder = null;
        reports = null;
        loadReports( breederId );
    }

    onMount( () => {
    });

    $: update( breederId )

    console.log( 'Breeder Reports', reports );

</script>

{#if reports}
    <Reports title={'Meldungen für Züchter {txt(breeder.firstname)} {txt(breeder.infix)} {txt(breeder.lastname)}'} {reports} />
{/if}


<style>

</style>