<script>
    import { meta } from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import List from "../common/List.svelte";

    import DistrictsTree from "../districts/DistrictsTree.svelte";
//    import {createEventDispatcher} from "svelte";

    let rootDistrict = null;

//    const dispatch = createEventDispatcher();
    const route = meta();


    function loadDistricts() {
        api.district.descendants.get( 1 ).then( response => {
            rootDistrict = filter( response.district );
        } );
    }

    function filter( source ) {
        let district = { id:source.id, name:source.name, moderated:true, children:[] };
        for( let child of source.children ) {
            district.children.push( filter( child ) );
        }
        return district;
    }

    $: loadDistricts( $user ); // if user changes by logout/login or exp
</script>


<List>
    <div slot='title'>Obmann {$user.fullname}</div>
    <div slot='header' >BDRG / LV / KV </div>
    <div slot='body'>
        {#if rootDistrict}
            <DistrictsTree {rootDistrict} />
        {/if}
    </div>
</List>

<style>

</style>