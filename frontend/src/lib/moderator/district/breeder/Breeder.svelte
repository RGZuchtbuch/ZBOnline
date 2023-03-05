<script>
    import {Route, router, meta} from 'tinro';
    import api from '../../../../js/api.js';
    import { newBreeder } from '../../../../js/template.js';
//    import {breeder} from '../../js/store.js';
    import Breeder from '../../../Breeder.svelte';
    import Report from '../Report.svelte';
    import Reports from '../Reports.svelte';

    export let id;
    export let districtId;

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

<div>
    Breeder {#if breeder} {breeder.name}{:else}...{/if}
</div>
{#if breeder}
    <Route path='/' redirect={meta.match+'/meldung'} />

    <Route path='/meldung/*' let:meta>
        <Route path='/' let:meta>
            <Reports promise={api.breeder.reports.get( breeder.id ) } />
        </Route>
        <Route path='/:reportId' let:meta> <Report reportId={ +meta.params.reportId } /></Route>
    </Route>

    <Route path='/daten' let:meta>
        Daten
        <Breeder {breeder} moderator={true} />
    </Route>
{/if}