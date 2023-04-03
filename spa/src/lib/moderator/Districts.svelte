<script>
    import { createEventDispatcher } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import DistrictRow from '../district/DistrictRow.svelte';

    let district = null;

    const dispatch = createEventDispatcher();
    const route = meta();


    // load whole district tree
    function loadDistrictTree() {
        api.district.descendants.get( 1 ).then( response => {
            district = filter( response.district );
            //setState( rootDistrict );
            //rootDistrict.open = true;
            console.log( 'DistrictTree', district );
        } );
    }

    function filter( source ) {
        let district = { id:source.id, name:source.name, moderated:(source.moderatorId===$user.id), children:[] };
        for( let child of source.children ) {
            let filteredChild = filter( child );
            if( filteredChild )
            district.children.push(  );
        }
        return district;
    }

    function setState( district, parentModerated=false  ) {
//        const children = district.children;
        district.moderated = parentModerated || ( $user && district.moderatorId && ( $user.id === district.moderatorId ) ); // is moderator
//        district.children = [];
        for( const child of district.children ) {
            const moderated = setState( child, district.moderated );
            console.log( 'Child', child.name, moderated );
//            if( moderated ) {
//                district.children.push(child);
//            }
        }
        console.log( "Mod State", district.name, district.moderated, district.children.length );
        return district.moderated ;
    }

    // forward district select to parent
    function onSelect( event ) {
        dispatch( 'select', event.detail );
    }

    $: loadDistrictTree( $user ); // if user changes by logout/login or exp

</script>


{#if $user}
    <h2 class='w-256 border border-gray-400 rounded-t p-2 bg-header text-center text-xl print'>Verbände für Obmann {$user.fullname} </h2>
    {#if district}
        <div class='w-256 bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 px-4 scrollbar'>
            <DistrictRow district={district} open={true} role='moderator' />
        </div>
    {:else}
        Einen moment bitte
    {/if}
{:else}
    Geht nicht, Sollte nicht
{/if}


<style>

</style>



