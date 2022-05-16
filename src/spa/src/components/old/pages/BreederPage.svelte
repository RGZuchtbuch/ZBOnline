<script>
    import { slide } from 'svelte/transition';

    import { Router, Route, Link, navigate } from 'svelte-navigator';
    import api from '../../../scripts/api.js';
    import Breeder from '../components/Breeder.svelte'
    import Pair from '../components/Pair.svelte';
    import Pairs from '../components/Pairs.svelte';
    import Results from '../components/Results.svelte';

    export let id;

    function onDetailsSelect( breederId ) {
        navigate( '/breeder/'+breederId );
    }
    function onPairSelect( pairId ) {
        navigate( '/breeder/'+id+'/pair/'+pairId );
    }
    function onPairsSelect( breederId ) {
        navigate( '/breeder/'+breederId+'/pairs');
    }
    function onResultsSelect( breederId ) {
        navigate( '/breeder/'+breederId+'/results');
    }

    let promise = api.getBreeder( id );

    console.log( 'run breederpage' )

</script>

<fieldset class='bordered w-256'>
    <legend> Mein Zuchtbuch </legend>
    <div class='flex flex-col'>
        <Breeder {id} {onDetailsSelect} {onPairsSelect} {onResultsSelect}/>
        <Route path='/'> <div>statistics</div> </Route>
        <Route path='pairs'> <Pairs promise={ api.getBreederPairs( id ) } {onPairSelect}/> </Route>
        <Route path='pair/:pairId' let:params> <Pair id={params.pairId} /> </Route>
        <Route path='results'> Pairs <Results promise={ api.getBreederResults( id ) } /> </Route>
    </div>
</fieldset>



<style>

</style>