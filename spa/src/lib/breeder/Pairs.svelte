<script>
    import { onMount } from 'svelte';
    import {dec, pct, txt} from '../../js/util.js';

    import api from '../../js/api.js';
    import {meta} from 'tinro';

    import Pairs from '../pairs/Pairs.svelte';

    export let breederId;
    let breeder;
    let pairs;

    const route = meta();

    function loadPairs( breederId ) {
        api.breeder.pairs.get( breederId ).then(response => {
            breeder = response.breeder;
            pairs = response.pairs;
        })
    }

    function update( breederId ) {
        breeder = null;
        pairs = null;
        loadPairs( breederId );
    }

    onMount( () => {
    });

    $: update( breederId )

</script>

{#if pairs}
    <Pairs title={`Meldungen für Züchter ${txt(breeder.firstname)} ${txt(breeder.infix)} ${txt(breeder.lastname)}`} {pairs} />
{/if}


<style>

</style>