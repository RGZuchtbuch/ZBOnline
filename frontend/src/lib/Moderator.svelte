

<script>
    import {Route, meta} from 'tinro';
    import api from '../js/api.js';

    import Breeder from './Breeder.svelte';
    import Breeders from './district/Breeders.svelte';
    import BreederReports from './breeder/Reports.svelte';
    import DistrictBreeders from './district/Breeders.svelte';
    import ModeratorDistricts from './moderator/Districts.svelte';
    import ResultsInput from './result/ResultsInput.svelte';
    import Report from './report/Report.svelte';
    import Results from './Results.svelte';

    const route = meta();

</script>

<Route path='/' redirect={ route.match+'/verband'} />

<Route path='/verband/*'>

    <Route path='/'> <ModeratorDistricts /> </Route>

    <Route path='/:districtId/*' let:meta>
        <Route path='/' redirect={meta.match+'/zuechter'} />

        <Route path='/zuechter/*' let:meta>
            <Route path='/' let:meta> <Breeders districtId={ meta.params.districtId } /> </Route>
            <Route path='/0' >New</Route>

            <Route path='/:breederId/*' let:meta >
                <Route path='/' redirect={meta.match+'/meldung'} />

                <Route path='/meldung/*' let:meta>
                    <Route path='/' let:meta> <BreederReports breederId={ meta.params.breederId } /> </Route>
                    <Route path='/:reportId' let:meta> <Report reportId={ meta.params.reportId } /></Route>
                </Route>

            </Route>
        </Route>
    </Route>
</Route>