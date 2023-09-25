<script>
    import {Route, meta, router} from 'tinro';
    import api from '../../js/api.js';
    import {txt} from '../../js/util.js';

    export let districtId;
    export let breederId;
    let breeder;

    const route = meta();

    function loadBreeder( id ) {
        if( id > 0 ) {
            api.breeder.get(id).then( response => {
                breeder = response.breeder;
            });
        } else {
            breeder = {
                id:0, firstname:null, infix:null, lastname:null,
                districtId:districtId, clubId:null, start:Date.now(), end:null,
                email:null, info:null
            }
        }
    }

    $: loadBreeder( breederId );
</script>

<div class='flex flex-col'>
    {#if districtId && breederId && breeder}
        <h4 class='whitespace-nowrap'> Züchter {txt(breeder.firstname).charAt(0)}. {txt(breeder.infix)} {txt(breeder.lastname)} </h4>
        <ul>
            <li> <a href={route.match+'/meldungen'} title='Zur Züchter Daten'>→ Meldungen </a> </li>
            <li> <a href={route.match+'/meldungen/0'} title='Zur Züchter Daten'>→ Melden </a> </li>
            <li> <a href={route.match+'/daten'} title='Zur Züchter Daten'>→ Daten </a> </li>
        </ul>
    {/if}
</div>

<style>

</style>