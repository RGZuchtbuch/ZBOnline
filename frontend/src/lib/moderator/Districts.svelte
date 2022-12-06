<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import SelectTree from '../SelectTree.svelte';

    const route = meta();





    let districts = null;

    function handle( userId ) {
        let promise = api.moderator.districts( userId );
        if( promise ) promise.then( data => {
            districts = data.districts;
        });
    }

    onMount( () => {
    })

    $: handle( $user.id );


</script>

<div class='grow flex flex-col bg-gray-100 border border-black rounded'>
    <h2 class='text-center'>{'Verbände für Obmann '+$user.name }</h2>
    <div class='bg-gray-200 px-4 font-bold'>Verbände</div>
    <div class='grow bg-gray-100 overflow-y-scroll border border-black scrollbar'>
        {#if districts}
            <SelectTree children={districts} link={'obmann/verband/'}/>
        {/if}
    </div>
</div>

<style>

</style>
