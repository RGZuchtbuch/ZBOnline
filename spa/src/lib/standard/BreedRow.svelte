<script>
    import api from '../../js/api.js';
    import { user } from '../../js/store.js';
    import ColorRow from './ColorRow.svelte';
    import {meta} from "tinro";

    export let breed = null;

    let open = false;

    const route = meta();

    function onOpen() {
        open = ! open;
    }

</script>

<div class='flex flex-col pl-8'>
    <div class='flex flex-row gap-x-1 border-b border-gray-300 my-1'>
        <div class='w-4'>⤷</div>
        <div class='grow cursor-pointer' on:click={onOpen}>
            {breed.name} ({breed.colors.length})
        </div>
        <div class='grow'></div>
        <div class='w-12 text-xs'>[{breed.id}]</div>
        <div class='w-4'> <a href={route.match+'/sparte/'+breed.sectionId+'/rasse/'+breed.id}> [+] </a> </div>
    </div>


    {#if open}
        {#each breed.colors as color }
            <ColorRow color={color}/>
        {/each}
    {/if}
</div>
