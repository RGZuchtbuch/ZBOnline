<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../js/api.js';

    export let id;
    export let legend = '';
    export let link='';

    let edit = false;
    let district = null;

    function handle( id ) {
        api.district.get( id ).then( data => {
            district = data.district;
        });
    }

    onMount( () => {})

    $: handle( id );


</script>

<main>
    {#if district}
        District ({district.id}) {district.fullname}
    {/if}
</main>

<style>

</style>