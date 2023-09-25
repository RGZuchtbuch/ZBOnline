<script>
    // show list of districts with add and gotodetails option
    import {meta} from "tinro";
    import {dat, txt} from '../../js/util.js';
    import {createEventDispatcher} from "svelte";
    import api from "../../js/api.js";
    import List from "../common/List.svelte";

    export let districtId = null;

    console.log('DistrictId', districtId  );
    let moderated = false;
    let show = false;
    let edit = false;
    let district = null;

    const route = meta();

    function loadChildren( districtId ) {
        api.district.descendants.get( districtId ).then( response => {
            district = response.district;
            console.log( 'Children', district );
        })
    }

    $: loadChildren( districtId );
</script>


{#if district && district.children}
    <List>
        <div slot='title'>{district.name} - Verbände / Vereine</div>
        <div slot='header' >--</div>
        <div slot='body'>
            {#each district.children as child }
                <div class='flex flex-row border-b border-gray-300 my-2'>
                    <div class='w-6'>→</div>
                    <a class='cursor-pointer' href={route.match+'/'+child.id} title='Zum Verein'>{child.name}</a>
                </div>
            {/each}
        </div>
    </List>
{/if}
