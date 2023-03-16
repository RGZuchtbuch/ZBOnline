<script>
    import {Route, meta} from 'tinro';
    import Map from './Map.svelte';


    const route = meta();

</script>

<Route path='/*' let:meta>
    <Map />
</Route>


