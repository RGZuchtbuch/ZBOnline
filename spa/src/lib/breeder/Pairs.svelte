<script>
    import {getContext, onMount} from 'svelte';
    import {dec, pct, txt} from '../../js/util.js';

    import api from '../../js/api.js';
    import {meta} from 'tinro';

    import Pairs from '../pairs/Pairs.svelte';

    let breeder = getContext( 'breeder' );
    let pairs;

    console.log( 'B', $breeder );

    const route = meta();

    function loadPairs( breederId ) {
        pairs = null;
        api.breeder.pairs.get( breederId ).then(response => {
            pairs = response.pairs;
        })
    }

    function update( breederId ) {
        loadPairs( breederId );
    }

    onMount( () => {
    });

    $: update( $breeder.id )

</script>

{#if $breeder && pairs}
    <Pairs title={`Meldungen für Züchter ${txt($breeder.firstname)} ${txt($breeder.infix)} ${txt($breeder.lastname)}`} {pairs} />
{/if}


<style>

</style>