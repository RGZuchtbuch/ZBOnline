<script>
    import {meta} from "tinro";
    import { dat, txt } from '../../js/util.js';
    import {createEventDispatcher} from "svelte";
    import api from '../../js/api.js';
    import {user} from '../../js/store.js';

    export let district = null;
    export let open = false;

    function toggleOpen() {
        open = ! open;
    }

    const route    = meta();


    console.log('User', $user )

</script>

<div class='flex flex-col pl-8'>
    <div class='flex border-b border-gray-300 my-2'>
        <div class='w-6 cursor-pointer' on:click={toggleOpen}>
            {#if 0 < district.children.length}
                {#if open}▲{:else}▼{/if}
            {/if}
        </div>
        <a class='cursor-pointer' href={route.match+'/'+district.id} title='Zum Verband'>{district.name} </a>
        <small class='w-8 text-center'> [{district.children.length}]</small>
        <div class='grow'></div>
        <small class='w-8 text-right'>[{district.id}]</small>
    </div>

    {#if district && ( open )}
        {#each district.children as childDistrict}
            <svelte:self district={childDistrict} />
        {/each}
    {/if}

</div>
