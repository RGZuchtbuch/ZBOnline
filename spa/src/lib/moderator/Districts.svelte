<script>
    import { createEventDispatcher } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import DistrictRow from '../district/DistrictRow.svelte';

    let district = null;

    const dispatch = createEventDispatcher();
    const route = meta();


    function loadDistricts() {
        api.district.descendants.get( 1 ).then( response => {
            district = filter( response.district );
            console.log("Admin districts root", district );
        } );
    }


    function filter( sourceDistrict ) {
        let moderated = $user && sourceDistrict && sourceDistrict.moderatorId === $user.id
        let district = { id:sourceDistrict.id, name:sourceDistrict.name, moderated:moderated, children:[] };

        let childModerated = false;
        for( let child of sourceDistrict.children ) {
            const filteredChild = filter( child );
            if( moderated || filteredChild.moderated || filteredChild.children.length > 0 ) {
                console.log( 'Filter', district.name, filteredChild.name, filteredChild.moderated );
                district.children.push( filteredChild );
            }
        }
        return district;
    }

    // forward district select to parent
    function onSelect( event ) {
        dispatch( 'select', event.detail );
    }

    $: loadDistricts( $user ); // if user changes by logout/login or exp

</script>


{#if $user}
    <h2 class='w-256 border border-gray-400 rounded-t p-2 bg-header text-center text-xl print'>Verbände für Obmann {$user.fullname} </h2>
    {#if district}
        <div class='w-256 bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 px-4 scrollbar'>
            <DistrictRow district={district} open={true} />
        </div>
    {:else}
        Einen moment bitte
    {/if}
{:else}
    Geht nicht, Sollte nicht
{/if}


<style>

</style>



