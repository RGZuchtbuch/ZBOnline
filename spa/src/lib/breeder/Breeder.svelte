<script>
    import {Route, router, meta} from 'tinro';
    import api from '../../js/api.js';
    import {txt} from '../../js/util.js';
    import { newBreeder } from '../../js/template.js';
    import Breeder from './account/Account.svelte';
    import Report from '../report/Report.svelte';
    import Reports from '../breeder/reports/Reports.svelte';
    import Results from './results/Results.svelte';
    import Account from "./account/Account.svelte";

    export let id = null;

    let breeder = null;

    let route = meta();

    function loadBreeder( id ) {
        if( id ) { // existing
            api.breeder.get(id).then(response => {
                //breeder.set( response.breeder );
                breeder = response.breeder;
            });
        } else { // new
            breeder = newBreeder( districtId );
            console.log( 'New breeder', breeder );
            router.goto( route.match+'/daten' );
        }
    }

    $: loadBreeder( id );

</script>

{#if breeder}
    <Route path='/' redirect={route.match+'/leistungen'} />

    <Route path='/meldung/*' let:meta> Results
        <Route path='/' let:meta>
            <Reports {breeder} />
        </Route>

        <Route path='/:reportId' let:meta> <Report reportId={ +meta.params.reportId } /></Route>
    </Route>


    <Route path='/daten' let:meta>

        <Account {breeder} />
    </Route>
{/if}