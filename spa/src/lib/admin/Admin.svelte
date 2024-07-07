<script>
    import {Route, meta, router} from 'tinro';

    import District from '../district/District.svelte';
    import Districts from './Districts.svelte';
    import Articles from './Articles.svelte';
    import Standard from '../standard/Standard.svelte';
    import Log from './log/Log.svelte';

    const route = meta();

    function onSelectDistrict( event ) {
        const url = route.match+'/verband/'+event.detail;
        router.goto( url );
    }

</script>
<main class='border border-red-500 p-2'>
    <div>Admin></div>
    <Route path='/' redirect={ route.match+'/verband'} />

    <Route path='/standard/*'> <Standard /> </Route>

    <Route path='/verband/*'>

        <Route path='/'>
            <Districts on:select={onSelectDistrict}/>
        </Route>

        <Route path='/:districtId/*' let:meta>
            <District districtId={meta.params.districtId} />
        </Route>
    </Route>

    <Route path='/seite' >
        <Articles />
    </Route>

    <Route path='/log' >
        <Log />
    </Route>

</main>