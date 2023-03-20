<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import District from './RecursiveDistrict.svelte';
    import ScrollDiv from '../common/ScrollDiv.svelte'

    let district = null;

    const route = meta();

    function loadDistricts() {
        api.district.descendants.get( 1 ).then( response => {
           district = response.district;
           district.open = true; // open top
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
    <ScrollDiv>
        <District district={district} />
    </ScrollDiv>
{:else}
    Einen moment bitte
{/if}


<style>

</style>
