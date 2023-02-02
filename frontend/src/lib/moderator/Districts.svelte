<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import Districts from '../Districts.svelte';

    let districts = null;


    const route = meta();

    function loadDistricts( user ) {
        api.moderator.districts().then( response => {
            districts = response.districts;
        });
    }

    $: loadDistricts( $user );

</script>

{#if $user}
    <h2 class='text-center'>Obmann {#if $user} {$user.name} {/if} → Verbände zum verwalten</h2>
    {#if districts} <Districts {districts} /> {/if}
{:else}
    NOT AUTORIZED
{/if}


<style>

</style>
