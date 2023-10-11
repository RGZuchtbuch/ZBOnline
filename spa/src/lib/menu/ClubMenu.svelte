<script>
    import api from '../../js/api.js';
    import {meta} from "tinro";


    export let districtId;
    export let clubId;

    let district;
    let club;

    const route = meta();

    function loadDistrict( id ) {
        api.district.get( id ).then( response => {
            district = response.district;
        })
    }
    function loadClub( id ) {
        api.district.get( id ).then( response => {
            club = response.district;
        })
    }

    $: loadDistrict( districtId );
    $: loadClub( clubId );
</script>

<div class='flex flex-col'>
    {#if club }
        <h3>Verein {club.short}</h3>
        <ul>
            <li> <a href={route.match+'/daten'} title='Zur Vereinsdaten'>→ Vereinsdaten </a> </li>


        </ul>
    {/if}
</div>

<style>

</style>