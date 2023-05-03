<script>
    import {Route, meta, router} from 'tinro';
    import api from '../../js/api.js';

    import District from '../district/District.svelte';
    import Districts from './DistrictsList.svelte';
    import Articles from './ArticlesList.svelte';
//    import Sections from './SectionsList.svelte';
    import Standard from '../standard/Standard.svelte';

    const route = meta();

    function onSelectDistrict( event ) {
        const url = route.match+'/verband/'+event.detail;
        console.log( 'Mod sel d', event.detail,  );
//        Router.location.query.set( 'district', event.detail, url );
        router.goto( url );
    }

</script>

<Route path='/' redirect={ route.match+'/verband'} />

<Route path='/standard'> <Standard /> </Route>

<Route path='/verband/*'>

    <Route path='/'>
        <Districts on:select={onSelectDistrict}/>
    </Route>

    <Route path='/:districtId/*' let:meta>
        <District id={meta.params.districtId} />
    </Route>
</Route>

<Route path='/seite' >
    <Articles />
</Route>


