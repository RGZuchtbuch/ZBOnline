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

    $: handle( $user.id ); // in case of user change ( login )


</script>

<h2 class='text-center'>Verbände für Obmann {$user.name}</h2>

<Districts districts={districts} />

<style>

</style>
