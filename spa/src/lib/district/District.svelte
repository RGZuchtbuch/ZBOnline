<script>
    import {Route, meta} from 'tinro';
    import api from '../../js/api.js';

    import Breeder from '../breeder/Breeder.svelte';
    import Breeders from '../breeders/Breeders.svelte';
    import DistrictResults from './DistrictReport.svelte';
    import ResultEdit from '../result/edit/Edit.svelte';
    import DistrictDetails from './DistrictDetails.svelte';
    import Club from '../club/Club.svelte';
    import ClubsList from '../club/ClubsList.svelte';

    export let districtId = null;
    let district = null;

    function loadDistrict( id ) {
        api.district.get( id ).then( response => {
            district = response.district;
        })
    }

    let route = meta();

    $: loadDistrict( districtId );
</script>

{#if district}

    <Route path='/' redirect={route.match+'/leistung'} />

    <Route path='/zuechter/*' let:meta>
        <Route path='/' let:meta>
            <Breeders {district} />
        </Route>

        <Route path='/:breederId/*' let:meta >
            <Breeder districtId={ meta.params.districtId } breederId={ meta.params.breederId } />
        </Route>
    </Route>

    <Route path='/leistung/*' let:meta>
        <Route path='/' let:meta> <DistrictResults districtId={ +meta.params.districtId } /> </Route>
        <Route path='/edit' let:meta> <ResultEdit districtId={ +meta.params.districtId }  /></Route>
    </Route>

    <Route path='/daten' let:meta>
        <DistrictDetails {districtId} />
    </Route>

    <Route path='/verein/*' let:meta>
        <Route path='/' let:meta> <ClubsList {districtId} /> </Route>
        <Route path='/:clubId/*' let:meta > <Club districtId={ +meta.params.districtId } clubId={ +meta.params.clubId } /> </Route>
    </Route>

{/if}