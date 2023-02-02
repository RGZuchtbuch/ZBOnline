<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import Breeders from '../Breeders.svelte';

    export let districtId = null;
    export let moderator = null;

    let district = null;
    let breeders = null;

    const route = meta();

    function loadBreeders( districtId ) {
        api.district.get( districtId ).then( response => {
            district = response.district;
        })
        api.district.breeders.get( districtId ).then( response => {
            breeders = response.breeders;
        });
    }

    $: loadBreeders( districtId ); //  init and when user changes by login

</script>


{#if district}
    <h2 class='text-center'>Züchter im {district.name}</h2>
    {#if breeders}
        <Breeders breeders={breeders} {moderator}/>
    {/if}
{:else}
    NOT AUTORIZED
{/if}


<style>

</style>
