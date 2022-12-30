<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import Districts from '../Districts.svelte';

    let districts = [];

    const route = meta();

    function handle( userId ) {
        let promise = api.moderator.districts( userId );
        if( promise ) promise.then( data => {
            districts = data.districts;
        });
    }

    onMount( () => {
    })
    console.log( 'User', $user );
    $: if( $user ) { // in case of user change ( login )
        handle( $user.id );
    } // no else, unintended user action


</script>
{#if $user}
    <h2 class='text-center'>Verbände für Obmann {$user.name}</h2>
    <Districts districts={districts} />
{:else}
    NOT AUTORIZED
{/if}
<style>

</style>
