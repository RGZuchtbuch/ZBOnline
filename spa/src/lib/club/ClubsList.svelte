<script>
    // show list of districts with add and gotodetails option
    import {meta} from "tinro";
    import {dat, txt} from '../../js/util.js';
    import {createEventDispatcher} from "svelte";
    import api from "../../js/api.js";
    import List from "../common/List.svelte";
    import ClubHeader from './ClubHeader.svelte';

    export let districtId = null;

    console.log('DistrictId', districtId  );
    let moderated = false;
    let show = false;
    let edit = false;
    let district = null;
    let clubs = null;

    const route = meta();

    function loadDistrict( districtId ) {
        api.district.descendants.get( districtId ).then( response => {
            district = response.district;
            district.clubs = district.children.filter( child => child.level=='OV' );
        })
    }


    $: loadDistrict( districtId );

</script>

aa
{#if district}
    <List>
        <div slot='title'>{district.name} - Vereine</div>
        <div slot='header' > <ClubHeader /></div>
        <div slot='body'>
            {#if district.clubs.length>0}
                {#each district.clubs as club }
                    <div class='flex flex-row border-b border-gray-300 my-2 gap-x-4'>
                        <div class='w-6'>→</div>
                        <a class='cursor-pointer' href={route.match+'/'+club.id} title='Zum Verein'>{club.name}</a>
                    </div>
                {/each}
            {:else}
                Keine Vereine bekannt.
            {/if}
        </div>
    </List>
{/if}
