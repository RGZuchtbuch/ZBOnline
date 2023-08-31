<script>
    import { onMount } from 'svelte';
    import {dec, pct, txt} from '../../js/util.js';

    import api from '../../js/api.js';
    import {meta} from 'tinro';

    import Reports from '../pairs/Pairs.svelte';
    import List from "../common/List.svelte";

    export let breederId;
    let breeder;
    let pairs;

    const route = meta();

    function loadReports( breederId ) {
        api.breeder.pairs.get( breederId ).then(response => {
            breeder = response.breeder;
            pairs = response.reports;
        })
    }

    function update( breederId ) {
        breeder = null;
        pairs = null;
        loadReports( breederId );
    }

    onMount( () => {
    });

    $: update( breederId )

</script>

{#if pairs}
    <Reports title={`Meldungen für Züchter ${txt(breeder.firstname)} ${txt(breeder.infix)} ${txt(breeder.lastname)}`} {pairs} />
{/if}


<style>

</style>