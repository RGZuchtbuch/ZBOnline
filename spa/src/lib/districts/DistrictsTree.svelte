<script>
    import {meta} from "tinro";
    import { dat, txt } from '../../js/util.js';
    import {createEventDispatcher} from "svelte";
    import api from '../../js/api.js';
    import {user} from '../../js/store.js';

    export let rootDistrict = null;

    let moderated = false;
    let show = false;
    let edit = false;
    let details = null;
    let changed = false;
    let children = null;
    let breeders = null;

    const route    = meta();

    console.log('User', $user )

</script>

<div class='flex flex-col pl-4'>
    {#if rootDistrict && ( rootDistrict.moderated || rootDistrict.children.length > 0 ) }
        <div class='flex border-b border-gray-300 my-2'>
            <div class='w-6'>&#10551;</div>

            {#if rootDistrict.moderated}
                <a class='cursor-pointer' href={route.match+'/'+rootDistrict.id} title='Zum Verband'>{rootDistrict.name}</a>
            {:else}
                <div class='text-gray-400 cursor-not-allowed' title='Kein zugang' >{rootDistrict.name}</div>
            {/if}
            {#if $user.admin}
                <small class='grow text-right'>[{rootDistrict.id}]</small>
            {/if}
        </div>
    {/if}

    {#if rootDistrict && ( true || open )}
        {#each rootDistrict.children as childDistrict}
            <svelte:self rootDistrict={childDistrict} />
        {/each}
    {/if}

</div>
