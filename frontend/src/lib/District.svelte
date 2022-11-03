<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../js/api.js';

    export let districtId;
    export let legend = '';
    export let link='';

    let district = null;

    function handle( districtId ) {
        api.district.get( districtId ).then( data => {
            district = data.district;
        });
    }

    onMount( () => {})

    $: handle( districtId );


</script>

<main>
    <div>{legend}</div>
    {#if district}
        District ({district.id}) {district.fullname}
    {/if}
</main>

<style>

</style>