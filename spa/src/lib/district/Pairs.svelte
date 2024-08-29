<script>
    import {getContext, onMount, setContext} from 'svelte';
    import {dec, pct, txt} from '../../js/util.js';

    import api from '../../js/api.js';
    import {meta} from 'tinro';

    import Pairs from '../pairs/Pairs.svelte';

    let district = getContext( 'district' );
    let breeder = getContext( 'breeder' );
    let pairs;

    const route = meta();

    function loadPairs( districtId ) {
        pairs = null;
        api.district.pairs.get( districtId ).then(response => {
            pairs = response.pairs;
        })
    }

    function update( districtId ) {
        loadPairs( districtId );
        breeder.set( null );
    }

    onMount( () => {
    });

    $: update( $district.id )

</script>

{#if pairs}
    <Pairs title={`Meldungen für Verband ${txt($district.name)}`} {pairs} />
{/if}


<style>

</style>