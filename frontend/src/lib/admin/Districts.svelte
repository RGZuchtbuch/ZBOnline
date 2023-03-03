<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import District from './RecursiveDistrict.svelte';

    let district = null;

    const route = meta();

    function loadDistricts() {
        api.district.descendants.get( 1 ).then( response => {
           district = response.district;
        } );
    }

//    function onSelect( district ) {
//        console.log('Click a', district.name, event);
//    }

    onMount( () => {})

    loadDistricts();
</script>


<h2 class='border border-gray-400 rounded-t p-2 bg-header text-center text-xl print'>Verbände im BDRG</h2>
{#if district}
    <div class='bg-gray-100 overflow-y-scroll border rounded-b border-t-0 border-gray-400 px-4 scrollbar'>
        <District district={district} />
    </div>
{:else}
    Einen moment bitte
{/if}


<style>

</style>
