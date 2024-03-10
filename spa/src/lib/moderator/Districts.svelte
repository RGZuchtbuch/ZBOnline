<script>
    import { meta } from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import List from "../common/List.svelte";

    import DistrictsTree from "../districts/DistrictsTree.svelte";

//    import DistrictsTree from '../admin/districts/DistrictsAdminTree.svelte';
//    import {createEventDispatcher} from "svelte";

    let district = null;

//    const dispatch = createEventDispatcher();
    const route = meta();


    function loadDistricts( user ) {
        api.district.descendants.get( 1 ).then( response => {
            district = filter( response.district );
        } );
    }

    function filter( sourceDistrict ) {
        let moderated = $user && sourceDistrict && sourceDistrict.moderatorId === $user.id
        let parentDistrict = { id:sourceDistrict.id, name:sourceDistrict.name, moderated:moderated, children:[] };

        for( let child of sourceDistrict.children ) {
            const childDistrict = filter( child );
            if( moderated || childDistrict.moderated || childDistrict.children.length > 0 ) {
                parentDistrict.children.push( childDistrict );
            }
        }
        return parentDistrict;
    }

    $: loadDistricts( $user ); // if user changes by logout/login or exp
</script>


<List>
    <div slot='title'>Obmann {$user.fullname}</div>
    <div slot='header' >BDRG / LV / KV </div>
    <div slot='body'>
        {#if district}
            <DistrictsTree {district} open={true}/>
        {/if}
    </div>
</List>

<style>

</style>