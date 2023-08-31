<script>
    import { createEventDispatcher } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../../js/api.js';
    import { user } from '../../../js/store.js'

    import DistrictRow from './DistrictRow.svelte';

    let rootDistrict = null;

    const dispatch = createEventDispatcher();
    const route = meta();


    function loadDistricts() {
        api.district.descendants.get( 1 ).then( response => {
            rootDistrict = filter( response.district );
        } );
    }


    function filter( sourceDistrict ) {
        let moderated = $user && sourceDistrict && sourceDistrict.moderatorId === $user.id
        let parentDistrict = { id:sourceDistrict.id, name:sourceDistrict.name, moderated:moderated, children:[] };

        let childModerated = false;
        for( let child of sourceDistrict.children ) {
            const childDistrict = filter( child );
            if( moderated || childDistrict.moderated || childDistrict.children.length > 0 ) {
                parentDistrict.children.push( childDistrict );
            }
        }
        return parentDistrict;
    }

    // forward district select to parent
    function onSelect( event ) {
        dispatch( 'select', event.detail );
    }

    $: loadDistricts( $user ); // if user changes by logout/login or exp

</script>


<h2 class='w-256 border border-gray-400 rounded-t p-2 bg-header text-center text-xl print'>Verbände für Obmann {$user.fullname} </h2>
{#if rootDistrict}
    <div class='w-256 bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 px-4 scrollbar'>
        <DistrictRow district={rootDistrict} open={true} />
    </div>
{/if}


<style>

</style>



