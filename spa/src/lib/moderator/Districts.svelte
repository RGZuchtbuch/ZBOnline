<script>
    import { createEventDispatcher } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import DistrictTree from '../district/DistrictTree.svelte';

    let rootDistrict = null;

    const dispatch = createEventDispatcher();
    const route = meta();


    // load whole district tree
    function loadDistrictTree() {
        api.district.descendants.get( 1 ).then( response => {
            rootDistrict = response.district;
            setState( rootDistrict );
            rootDistrict.open = true;
            console.log( 'DistrictTree', rootDistrict );
        } );
    }

    function setState( district, parentModerated=false ) {
        const children = district.children;
        district.moderated = parentModerated || ( $user && ( $user.id === district.moderatorId ) ); // is moderator
        district.children = [];
        for( const child of children ) {
            const moderated = setState( child, district.moderated );
            console.log( 'Child', child.name, moderated );
            if( moderated ) {
                district.children.push(child);
            }
        }
        console.log( district.name, district.moderated, district.children.length );
        return district.moderated || district.children.length > 0 ;
    }

    // forward district select to parent
    function onSelect( event ) {
        dispatch( 'select', event.detail );
    }

    $: loadDistrictTree( $user ); // if user changes by logout/login or exp

</script>


{#if $user}
    <h2 class='w-256 border border-gray-400 rounded-t p-2 bg-header text-center text-xl print'>Verbände für Obmann {$user.fullname} </h2>
    {#if rootDistrict}
        <div class='w-256 bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 px-4 scrollbar'>
            <DistrictTree district={rootDistrict} on:select={onSelect} open={true}/>
        </div>
    {:else}
        Einen moment bitte
    {/if}
{:else}
    Geht nicht, Sollte nicht
{/if}


<style>

</style>



