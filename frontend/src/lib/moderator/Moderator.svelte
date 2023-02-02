<script>
    import {Route, meta} from 'tinro';
    import api from '../../js/api.js';
    import {user, district, breeder} from '../../js/store.js';

    import Breeder from './Breeder.svelte';
    import Breeders from './Breeders.svelte';
    import Reports from './Reports.svelte';
    import Results from './Results.svelte';
    import District from './District.svelte';
    import Districts from './Districts.svelte';
    import Report from './Report.svelte';
    import DistrictResultsEdit from '../district/ResultsEdit.svelte';

    const route = meta();
    $: console.log( 'Params', route, route.params );

</script>

<Route path='/' redirect={ route.match+'/verband'} />

<Route path='/verband/*'>

    <Route path='/'> <Districts /> </Route>

    <Route path='/:districtId/*' let:meta>
        <District id={meta.params.districtId} >

        </District>
    </Route>
</Route>