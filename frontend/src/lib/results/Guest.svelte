<script>
    import {Route, meta} from 'tinro';
    import Map from './Map.svelte';


    const route = meta();

</script>

<Route path='/' redirect={ route.match+'/karte'} />

<Route path='/karte/*' let:meta>
    <Map query={meta.query} />
</Route>


