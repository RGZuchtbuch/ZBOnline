

<script>
    import {Route, meta} from 'tinro';
    import api from '../../js/api.js';

    import Map from './Map.svelte';
    import Trend from './Trend.svelte';

    const route = meta();

</script>

<Route path='/' redirect={ route.match+'/karte'} />

<Route path='/karte/*' let:meta>
    <Map query={meta.query} />
</Route>

<Route path='/trend/*'>
    <Trend />
</Route>
