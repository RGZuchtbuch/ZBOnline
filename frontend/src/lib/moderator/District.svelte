<script>
    import {Route, meta} from 'tinro';
    import api from '../../js/api.js';
    import {district, user} from '../../js/store.js';

    import Breeder from './Breeder.svelte';
    import Breeders from './Breeders.svelte';
    import Reports from './Reports.svelte';
    import Results from './Results.svelte';
    import Report from './Report.svelte';
    import DistrictResultsEdit from '../district/ResultsEdit.svelte';

    export let id;
//    let district = null;

    function loadDistrict( id ) {
        api.district.get( id ).then( response => {
  //          district = response.district;
            district.set( response.district );
        })
    }

    let route = meta();

    $: loadDistrict( id );

</script>

{#if district}
    <Route path='/' redirect={route.match+'/zuechter'} />

    <Route path='/zuechter/*' let:meta>
        <Route path='/' let:meta>
            <Breeders districtId={ +meta.params.districtId } moderator={true} />
        </Route>

        <Route path='/:breederId/*' let:meta >
            <Breeder id={ +meta.params.breederId } districtId={id} moderator={true} edit={true}>

            </Breeder>
        </Route>
    </Route>

    <Route path='/leistung/*' let:meta>
        <Route path='/' let:meta> <Results districtId={ +meta.params.districtId } /> </Route>
        <Route path='/edit' let:meta> <DistrictResultsEdit districtId={ +meta.params.districtId }  /></Route>
    </Route>{/if}