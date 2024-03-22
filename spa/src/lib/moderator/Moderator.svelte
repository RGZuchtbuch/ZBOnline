<script>
    import { meta, router, Route } from 'tinro';

    import District from '../district/District.svelte';
    import Districts from './Districts.svelte';
    import Grading from '../moderator/Grading.svelte';

    const route = meta();

    function onSelectDistrict( event ) {
        const url = route.match+'/verband/'+event.detail;
        router.goto( url );
    }

</script>

<Route path='/' redirect={ route.match+'/verband'} />

<Route path='/verband/*'>

    <Route path='/'> <Districts on:select={onSelectDistrict}/> </Route>


    <Route path='/:districtId/*' let:meta>
        <District districtId={meta.params.districtId} />
    </Route>
</Route>

<Route path='/grading'>
    <Grading />
</Route>