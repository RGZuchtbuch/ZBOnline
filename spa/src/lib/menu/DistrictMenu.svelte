<script>
    import api from '../../js/api.js';
    import {meta} from "tinro";


    export let districtId;
    let district;

    const route = meta();

    function loadDistrict( id ) {
        api.district.get( id ).then( response => {
            district = response.district;
        })
    }

    $: loadDistrict( districtId );
</script>

<div class='flex flex-col'>
    {#if districtId && district}
        <h3>Verband {district.short}</h3>
        <ul>
            <li> <a href={route.match+'/leistung'} title='Zur Verbandsdaten'>→ Leistungen </a> </li>
            <li> <a href={route.match+'/leistung/edit'} title='Zur Verbandsdaten'>→ Eingeben </a> </li>
            <div class='h-2'></div>
            <li> <a href={route.match+'/zuechter'} title='Zur Verbandsdaten'>→ Züchter </a> </li>
            <li> <a href={route.match+'/verein'} title='Zu den Vereinen'>→ Vereine</a> </li>
            <div class='h-2'></div>
            <li> <a href={route.match+'/daten'} title='Zur Verbandsdaten'>→ Verbandsdaten </a> </li>

        </ul>
    {/if}
</div>

<style>

</style>