<script>
    import {createEventDispatcher, onMount} from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import { user } from '../../js/store.js'
    import DistrictTree from '../district/DistrictTree.svelte';
    import ScrollDiv from '../common/ScrollDiv.svelte'
    import api from '../../js/api.js';


    let rootDistrict = null;

    const route = meta();
    const dispatch = createEventDispatcher();

    function loadDistricts() {
        api.district.descendants.get( 1 ).then( response => {
            rootDistrict = response.district;
            setState( rootDistrict );
            rootDistrict.open = true; // open top
            console.log("Admin districts root", rootDistrict );
        } );
    }

    function setState( district, parentModerated=false ) {
        district.moderated = $user && $user.admin; // is admin
        district.childModerated = false;
        for( const childDistrict of district.children ) {
            district.childModerated |= setState( childDistrict, district.moderated );
        }
        return district.moderated;
    }

    onMount( () => {})

    loadDistricts();
</script>

{#if $user}
    <h2 class='w-256 border border-gray-400 rounded-t p-2 bg-header text-center text-xl print'>Verbände für Obmann {$user.fullname} </h2>
    {#if rootDistrict}
        <div class='w-256 bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 px-4 scrollbar'>
            <DistrictTree district={rootDistrict} open={true}/>
        </div>
    {:else}
        Einen moment bitte
    {/if}
{:else}
    Geht nicht, Sollte nicht
{/if}

<style></style>