<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { district, user } from '../../js/store.js'
    import Breeders from '../Breeders.svelte';

    export let districtId = null;
    export let moderator = null;

    let breeders = null;

    function handle( districtId ) {
        if( districtId ) {
            api.district.breeders.get( districtId ).then( response => {
                breeders = response.breeders;
            })
        }
    }

    const route = meta();

    onMount( () => {
    })

    $: handle( districtId );

</script>

{#if $user}
    <h2 class='text-center'>Obmann {#if $user} {$user.name} {/if} → Verband {#if $district} {$district.name} {/if} → Züchter</h2>
    {#if breeders} <Breeders breeders={breeders} moderator={true} /> {/if}
{:else}
    NOT AUTORIZED
{/if}



<style>

</style>