<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';

    import Breeders from '../Breeders.svelte';


    export let districtId;

    const route = meta();
    let district;
    let breeders = [];

    function handle( districtId ) {
        api.district.get( districtId ).then( data => {
            district = data.district;
            console.log( district );
        })
        api.district.breeders.get( districtId ).then( data => {
            breeders = data.breeders;
        });
    }

    onMount( () => {
    })

    $: handle( districtId );
    //promise={api.district.breeders.get(meta.params.districtId) } legend='Züchter'

</script>


{#if district}
    <h2 class='text-center'>Züchter für Verband {district.name}</h2>
{/if}
<Breeders breeders={breeders} />


<style>

</style>