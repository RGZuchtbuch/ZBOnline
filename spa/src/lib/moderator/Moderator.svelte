<script>
    import { meta, router, Route } from 'tinro';
    import api from '../../js/api.js';
    import {user} from '../../js/store.js';

    import Comment from '../common/Comment.svelte';
    import District from '../district/District.svelte';
    import Districts from './Districts.svelte';

    const route = meta();

    function onSelectDistrict( event ) {
        const url = route.match+'/verband/'+event.detail;
        console.log( 'Mod sel d', event.detail,  );
//        Router.location.query.set( 'district', event.detail, url );
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