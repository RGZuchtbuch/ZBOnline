<script>
    import { meta, router, Route } from 'tinro';

    import District from '../district/District.svelte';
    import Districts from './Districts.svelte';
    import Grading from '../result/Grading.svelte';


    const route = meta();

    function onSelectDistrict( event ) {
        const url = route.match+'/verband/'+event.detail;
        router.goto( url );
    }

</script>

<Route path='/' redirect={ route.match+'/verband'} />
<Route path='/nachweis'> <Grading /> </Route>

<Route path='/verband/*'>

    <Route path='/'> <Districts on:select={onSelectDistrict}/> </Route>

    <Route path='/:districtId/*' let:meta>
        <District districtId={meta.params.districtId} />
    </Route>

</Route>



