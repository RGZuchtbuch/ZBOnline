<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import Districts from '../Districts.svelte';

    let districts = null;

    const route = meta();

    function loadDistricts( user ) {
        api.moderator.districts( user.id ).then( response => {
            districts = response.districts;
        });
    }

    $: loadDistricts( $user );

</script>
{#if $user}
    <h2 class='text-center'>Verbände für Obmann {$user.name}</h2>
    <Districts districts={districts} />
{:else}
    NOT AUTORIZED
{/if}
<style>

</style>
