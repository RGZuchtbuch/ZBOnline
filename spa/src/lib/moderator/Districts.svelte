<script>
    import { createEventDispatcher } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import RecursiveDistrict from './RecursiveDistrict.svelte';

    let rootDistrict = null;

    const dispatch = createEventDispatcher();
    const route = meta();

    // set visible and editable as appropriate
    // editable if moderator for district or its parent
    // visible if editable or some child is editable
    function checkEditable( district, selectableParent ) {
        district.selectable = $user && ( $user.moderator.indexOf( district.id )>=0 );
        district.editable = district.selectable || selectableParent

        let selectableChild = false;
        for( const childDistrict of district.children ) {
            selectableChild |= checkEditable( childDistrict, district.editable );
        }
        district.visible = selectableParent || district.selectable || selectableChild;

        if( district.children && selectableChild ) district.open = true;
        return district.selectable;
    }

    function setState( district, parentModerated ) {
        district.moderated = parentModerated || ( $user && ( $user.moderator.indexOf( district.id )>=0 ) ); // is moderator
        district.childModerated = false;
        for( const childDistrict of district.children ) {
            district.childModerated |= setState( childDistrict );
        }
        return district.moderated;
    }

    // load whole district tree
    function loadRootDistrict() {
        api.district.descendants.get( 1 ).then( response => {
            rootDistrict = response.district;
            setState( rootDistrict );
            rootDistrict.open = true;
        } );
    }

    // forward district select to parent
    function onSelect( event ) {
        dispatch( 'select', event.detail );
    }

    $: loadRootDistrict( $user ); // if user changes by logout/login or exp

</script>


{#if $user}
    <h2 class='border border-gray-400 rounded-t p-2 bg-header text-center text-xl print'>Verbände für Obmann {$user.fullname} </h2>
    {#if rootDistrict}
        <div class='bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 px-4 scrollbar'>
            <RecursiveDistrict district={rootDistrict} on:select={onSelect}/>
        </div>
    {:else}
        Einen moment bitte
    {/if}
{:else}
    Geht nicht, Sollte nicht
{/if}


<style>

</style>



