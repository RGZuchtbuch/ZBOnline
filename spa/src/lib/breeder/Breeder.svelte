<script>
    import {Route, router, meta} from 'tinro';
    import api from '../../js/api.js';
    import {txt} from '../../js/util.js';
    import Pair from '../pair/Pair.svelte';
    import BreederPairs from './Pairs.svelte';
    import BreederDetails from "./BreederDetails.svelte";

    export let breederId
    export let districtId;;

    let breeder = null;
    let route = meta();

    function loadBreeder( id ) {
        if( id && id>0 ) { // valid id, else new
            api.breeder.get(id).then(response => {
                //breeder.set( response.breeder );
                breeder = response.breeder;
            });
        } else { // new
            breeder = {
                id:null, firstname:null, infix: null, lastname:null, active:true,
                districtId: districtId,
                district:{ id:districtId, name:null },
                clubId:null,
                club:{ id:null, name:null },
                start: Date.now(), end: null,
                email: null,
                info: null
            };;
            //router.goto( route.match+'/daten' );
        }
    }

    $: loadBreeder( breederId );

</script>

{#if breeder}
    <Route path='/' redirect={route.match+'/meldung'} />

    <Route path='/daten' let:meta>
        <BreederDetails {breeder} />
    </Route>
    <Route path='/meldung/*' let:meta>
        <Route path='/' let:meta>
            <BreederPairs breederId={meta.params.breederId} />
        </Route>

        <Route path='/:pairId' let:meta> <Pair id={meta.params.pairId} districtId={meta.params.districtId} breederId={meta.params.breederId}/></Route>
    </Route>

{/if}