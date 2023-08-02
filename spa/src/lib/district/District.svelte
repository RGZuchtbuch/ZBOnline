<script>
    import {Route, meta} from 'tinro';
    import api from '../../js/api.js';

    import Breeder from '../breeder/Breeder.svelte';
    import Breeders from './breeders/Breeders.svelte';
    import Reports from './reports/Reports.svelte';
    import ResultsList from './results/DistrictResultsList.svelte';
    import Report from '../report/Report.svelte';
    import ResultsEdit from './results/ResultsEdit.svelte';
    import ResultEdit from '../result/edit/Edit.svelte';

    export let id = null;
    let district = null;

    function loadDistrict( id ) {
        api.district.get( id ).then( response => {
            district = response.district;
        })
    }

    let route = meta();

    $: loadDistrict( id );

</script>

{#if district}
    <Route path='/' redirect={route.match+'/zuechter'} />

    <Route path='/zuechter/*' let:meta>
        <Route path='/' let:meta>
            <Breeders {district} />
        </Route>

        <Route path='/:breederId/*' let:meta >
            <Breeder id={ +meta.params.breederId } districtId={id} moderator={true} edit={true} />
        </Route>
    </Route>

    <Route path='/leistung/*' let:meta>
        <Route path='/' let:meta> <ResultsList districtId={ +meta.params.districtId } /> </Route>
        <Route path='/edit' let:meta> <ResultEdit districtId={ +meta.params.districtId }  /></Route>
    </Route>
{/if}