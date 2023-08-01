<script>
    import { onMount } from 'svelte';
    import {active, meta, router, Route} from 'tinro';
    import api from '../../js/api.js';
    import { user } from '../../js/store.js'

    import BreederList from '../breeder/BreederList.svelte';
//    import Date from "../common/input/Date.svelte";

    export let district = null;

    let breeders = null;
    let clubs = null;

    let allBreeders = false;

    const route = meta();

    function onAddBreeder() { // event

        let breeder = {
            id: null,
            firstname: null,
            infix: null,
            lastname: null,
            email: null,
            districtId: district.id,
            districtName: district.name,
            clubId: null,
            clubName: null,
            start: Date.now(),
            end: null,
            active: true,
            info: null
        };
        breeders.unshift(breeder);
        breeders = breeders;
    }


    function loadBreeders( district ) {
        api.district.breeders.get( district.id ).then( response => {
           breeders = response.breeders;
           console.log( 'Breeders', breeders );
        });
    }

    onMount( () => {
    })


    $: loadBreeders( district );

    console.log( 'Check', $user.admin, $user.id, district.moderatorId );

</script>
{#if $user && ( $user.admin || $user.id === district.moderatorId ) }

    <h3 class='w-256 text-center'>
        Zuchtbuchmitglieder im {district.name}
    </h3>

    <div class='w-256 bg-gray-100 overflow-y-scroll border border-gray-400 rounded scrollbar'>
        {#if breeders}
            <BreederList {breeders} on:addBreeder={onAddBreeder}/>
        {/if}
    </div>
{:else}
    NOT AUTORIZED
{/if}



<style>

</style>