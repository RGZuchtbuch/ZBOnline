<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import District from '../RecursiveDistrict.svelte';


    let district = null;


    const route = meta();

    function checkEditable( district ) {
        let editableChild = false;
        for( const childDistrict of district.children ) {
            editableChild |= checkEditable( childDistrict );
        }

        district.editable = $user && $user.moderator.indexOf( district.id )>=0;
        district.visible = district.editable || editableChild;
        console.log( 'Districts', district );
        return district.visible;
    }

    function loadDistrict() {
        api.district.root.get( 1 ).then( response => {
            district = response.district;
            checkEditable( district );
        } );
    }

/*    function loadDistricts( user ) {
        api.moderator.district.root( 1 ).then( response => {
            //districts = response.districts;
            district = response.district;
        });
    }
*/
    $: loadDistrict( $user );

</script>


{#if $user}
    <h2 class='border border-gray-400 rounded-t p-2 bg-header text-center text-xl print'>Verbände für Obmann {$user.name} </h2>
    {#if district}
        <div class='bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 px-4 scrollbar'>
            <District district={district} />
        </div>
    {:else}
        Einen moment bitte
    {/if}
{:else}
    Geht nicht, Sollte nicht
{/if}


<style>

</style>



